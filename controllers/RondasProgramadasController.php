<?php

namespace app\controllers;

use Yii;
use app\models\AlumnosServicioSocial;
use app\models\RondasProgramadas;
use app\models\Verificaciones;
use app\models\HorariosServicioSocial;
use app\models\Sanitarios;
use app\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class RondasProgramadasController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => static function () {
                            $identity = Yii::$app->user->identity;
                            if (!$identity instanceof User) {
                                return false;
                            }
                            $rol = (int) $identity->id_rol;
                            $accion = Yii::$app->controller->action->id;
                            return ($rol === 2 && in_array($accion, ['index', 'iniciar'], true))
                                || ($rol === 1 && in_array($accion, ['index', 'create'], true));
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => ['iniciar' => ['POST']],
            ],
        ];
    }

    public function actionIndex()
    {
        $esAdministrador = (int) $this->getIdentity()->id_rol === 1;
        $alumno = $esAdministrador ? null : $this->getAlumno();
        $condicionAsignacion = $esAdministrador
            ? '1 = 1'
            : 'EXISTS (
                 SELECT 1
                 FROM {{%alumno_horarios_ss}} ahs
                 WHERE ahs.id_alumno = :id_alumno
                   AND ahs.id_horario_ss = rp.id_horario_ss
                   AND ahs.estado = 1
                   AND ahs.fecha_inicio <= rp.fecha_programada
                   AND (ahs.fecha_fin IS NULL OR ahs.fecha_fin >= rp.fecha_programada)
             )';
        $params = $esAdministrador ? [] : [':id_alumno' => $alumno->id_alumno];
        $rondas = Yii::$app->db->createCommand(
            'SELECT rp.id_ronda, rp.fecha_programada, rp.hora_programada, rp.estado,
                    s.nombre AS sanitario_nombre, e.nombre AS edificio_nombre,
                    hss.nombre_turno,
                    v.id_verificacion, v.id_alumno AS alumno_verificador,
                    v.fecha_hora_fin
             FROM {{%rondas_programadas}} rp
             INNER JOIN {{%sanitarios}} s ON s.id_sanitario = rp.id_sanitario
             INNER JOIN {{%edificios}} e ON e.id_edificio = s.id_edificio
             INNER JOIN {{%horarios_servicio_social}} hss ON hss.id_horario_ss = rp.id_horario_ss
             LEFT JOIN {{%verificaciones}} v ON v.id_ronda = rp.id_ronda
             WHERE ' . $condicionAsignacion . '
             ORDER BY rp.fecha_programada DESC, rp.hora_programada DESC',
            $params
        )->queryAll();

        return $this->render('index', [
            'rondas' => $rondas,
            'idAlumno' => $alumno === null ? null : (int) $alumno->id_alumno,
            'esAdministrador' => $esAdministrador,
        ]);
    }

    public function actionCreate()
    {
        $model = new RondasProgramadas();
        $model->estado = 'Pendiente';
        $model->fecha_programada = date('Y-m-d');

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->estado = 'Pendiente';
            $model->fecha_hora_inicio = null;
            $model->fecha_hora_fin = null;
            if ($model->validate()) {
                $horario = HorariosServicioSocial::findOne([
                    'id_horario_ss' => $model->id_horario_ss,
                    'estado' => 1,
                ]);
                $diasSemana = [
                    1 => 'Lunes',
                    2 => 'Martes',
                    3 => 'Miércoles',
                    4 => 'Jueves',
                    5 => 'Viernes',
                    6 => 'Sábado',
                    7 => 'Domingo',
                ];
                if ($horario === null) {
                    $model->addError('id_horario_ss', 'Selecciona un horario activo.');
                } elseif ($diasSemana[(int) date('N', strtotime($model->fecha_programada))] !== $horario->dia_semana) {
                    $model->addError('fecha_programada', 'La fecha debe coincidir con el día del horario seleccionado.');
                } elseif (!$this->horaDentroDelHorario($model->hora_programada, $horario->hora_inicio, $horario->hora_fin)) {
                    $model->addError('hora_programada', 'La hora debe estar dentro del horario seleccionado.');
                }

                $tieneAlumnos = !$model->hasErrors() && (bool) Yii::$app->db->createCommand(
                    'SELECT EXISTS (
                        SELECT 1
                        FROM {{%alumno_horarios_ss}} ahs
                        WHERE ahs.id_horario_ss = :id_horario_ss
                          AND ahs.estado = 1
                          AND ahs.fecha_inicio <= :fecha
                          AND (ahs.fecha_fin IS NULL OR ahs.fecha_fin >= :fecha)
                    )',
                    [
                        ':id_horario_ss' => $model->id_horario_ss,
                        ':fecha' => $model->fecha_programada,
                    ]
                )->queryScalar();
                if (!$model->hasErrors() && !$tieneAlumnos) {
                    $model->addError('id_horario_ss', 'No hay alumnos activos asignados a ese horario en la fecha seleccionada.');
                } elseif (!$model->hasErrors() && $model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Ronda programada correctamente.');
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'sanitarios' => Sanitarios::find()->where(['estado' => 1])->orderBy(['nombre' => SORT_ASC])->all(),
            'horarios' => HorariosServicioSocial::find()->where(['estado' => 1])->orderBy(['dia_semana' => SORT_ASC, 'hora_inicio' => SORT_ASC])->all(),
        ]);
    }

    public function actionIniciar($id_ronda)
    {
        $alumno = $this->getAlumno();
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            $ronda = $db->createCommand(
                'SELECT rp.*
                 FROM {{%rondas_programadas}} rp
                 WHERE rp.id_ronda = :id_ronda
                   AND EXISTS (
                       SELECT 1
                       FROM {{%alumno_horarios_ss}} ahs
                       WHERE ahs.id_alumno = :id_alumno
                         AND ahs.id_horario_ss = rp.id_horario_ss
                         AND ahs.estado = 1
                         AND ahs.fecha_inicio <= rp.fecha_programada
                         AND (ahs.fecha_fin IS NULL OR ahs.fecha_fin >= rp.fecha_programada)
                   )
                 FOR UPDATE',
                [':id_ronda' => $id_ronda, ':id_alumno' => $alumno->id_alumno]
            )->queryOne();

            if ($ronda === false) {
                throw new NotFoundHttpException('La ronda no existe o no está asignada a tu horario.');
            }

            $verificacion = Verificaciones::findOne(['id_ronda' => $id_ronda]);
            if ($verificacion !== null) {
                $transaction->commit();
                if ((int) $verificacion->id_alumno !== (int) $alumno->id_alumno) {
                    Yii::$app->session->setFlash('error', 'Esta ronda ya fue atendida por otro alumno.');
                    return $this->redirect(['index']);
                }
                return $this->redirect(['verificaciones/finalizar', 'id_verificacion' => $verificacion->id_verificacion]);
            }

            if (mb_strtolower($ronda['estado'], 'UTF-8') === 'completada') {
                $transaction->commit();
                Yii::$app->session->setFlash('error', 'Esta ronda ya está marcada como completada.');
                return $this->redirect(['index']);
            }

            $fechaInicio = date('Y-m-d H:i:s');
            $verificacion = new Verificaciones();
            $verificacion->id_ronda = (int) $ronda['id_ronda'];
            $verificacion->id_alumno = (int) $alumno->id_alumno;
            $verificacion->id_sanitario = (int) $ronda['id_sanitario'];
            $verificacion->fecha_hora_inicio = $fechaInicio;

            if (!$verificacion->save()) {
                throw new \RuntimeException('No se pudo iniciar la ronda: ' . json_encode($verificacion->errors, JSON_UNESCAPED_UNICODE));
            }

            $db->createCommand(
                'UPDATE {{%rondas_programadas}}
                 SET estado = :estado, fecha_hora_inicio = COALESCE(fecha_hora_inicio, :fecha_inicio)
                 WHERE id_ronda = :id_ronda',
                [':estado' => 'En curso', ':fecha_inicio' => $fechaInicio, ':id_ronda' => $id_ronda]
            )->execute();

            $transaction->commit();
            return $this->redirect(['verificaciones/finalizar', 'id_verificacion' => $verificacion->id_verificacion]);
        } catch (\Throwable $exception) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            throw $exception;
        }
    }

    private function getAlumno(): AlumnosServicioSocial
    {
        $alumno = AlumnosServicioSocial::findOne([
            'id_usuario' => $this->getIdentity()->getId(),
            'estado' => 1,
        ]);
        if ($alumno === null) {
            throw new ForbiddenHttpException('Tu cuenta no está vinculada a un alumno activo de servicio social.');
        }
        return $alumno;
    }

    private function getIdentity(): User
    {
        $identity = Yii::$app->user->identity;
        if (!$identity instanceof User) {
            throw new ForbiddenHttpException('La sesión actual no corresponde a una cuenta válida.');
        }
        return $identity;
    }

    private function horaDentroDelHorario(string $hora, string $inicio, string $fin): bool
    {
        $hora = substr($hora, 0, 5);
        $inicio = substr($inicio, 0, 5);
        $fin = substr($fin, 0, 5);

        if ($inicio <= $fin) {
            return $hora >= $inicio && $hora <= $fin;
        }
        return $hora >= $inicio || $hora <= $fin;
    }
}
