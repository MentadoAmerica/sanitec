<?php

namespace app\controllers;

use Yii;
use app\models\RondasProgramadas;
use app\models\HorariosServicioSocial;
use app\models\Sanitarios;
use app\models\Edificios;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\db\Query;

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
                        'matchCallback' => function () {
                            $rol = (int) Yii::$app->user->identity->id_rol;
                            return in_array($rol, [1, 2], true);
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $hoy = date('Y-m-d');

        $filas = (new Query())
            ->select([
                'rp.id_ronda',
                'rp.estado AS estado_ronda',
                'rp.hora_programada',
                'rp.fecha_programada',
                'hss.nombre_turno',
                'hss.hora_inicio',
                'hss.hora_fin',
                's.id_sanitario',
                's.nombre AS sanitario_nombre',
                'e.id_edificio',
                'e.nombre AS edificio_nombre',
                'v.id_verificacion',
                'v.fecha_hora_fin AS verificacion_fin',
            ])
            ->from(['rp' => '{{%rondas_programadas}}'])
            ->innerJoin(['hss' => '{{%horarios_servicio_social}}'], 'hss.id_horario_ss = rp.id_horario_ss')
            ->innerJoin(['s' => '{{%sanitarios}}'], 's.id_sanitario = rp.id_sanitario')
            ->innerJoin(['e' => '{{%edificios}}'], 'e.id_edificio = s.id_edificio')
            ->leftJoin(['v' => '{{%verificaciones}}'],
                'v.id_ronda = rp.id_ronda AND v.fecha_hora_fin IS NOT NULL')
            ->where(['rp.fecha_programada' => $hoy])
            ->orderBy([
                'hss.hora_inicio' => SORT_ASC,
                'e.nombre' => SORT_ASC,
                'rp.hora_programada' => SORT_ASC,
            ])
            ->all();

        $grupos = [];
        foreach ($filas as $f) {
            $clave = $f['nombre_turno'] . '|' . $f['edificio_nombre'] . '|' . $f['hora_inicio'];
            if (!isset($grupos[$clave])) {
                $grupos[$clave] = [
                    'turno' => $f['nombre_turno'],
                    'horario' => substr($f['hora_inicio'], 0, 5) . ' - ' . substr($f['hora_fin'], 0, 5),
                    'edificio' => $f['edificio_nombre'],
                    'id_edificio' => $f['id_edificio'],
                    'sanitarios' => [],
                ];
            }
            $grupos[$clave]['sanitarios'][] = $f;
        }

        $esAdmin = (int) Yii::$app->user->identity->id_rol === 1;

        return $this->render('index', [
            'grupos' => array_values($grupos),
            'fecha' => $hoy,
            'esAdmin' => $esAdmin,
        ]);
    }

    public function actionCreate()
{
    $model = new RondasProgramadas();
    $model->fecha_programada = date('Y-m-d');
    $model->estado = 'Pendiente';

    $turnos = HorariosServicioSocial::find()
        ->where(['estado' => 1])
        ->orderBy(['dia_semana' => SORT_ASC, 'hora_inicio' => SORT_ASC])
        ->all();

    $sanitarios = Sanitarios::find()
        ->alias('s')
        ->select(['s.id_sanitario', 's.nombre', 's.tipo', 'e.nombre AS edificio'])
        ->innerJoin(['e' => '{{%edificios}}'], 'e.id_edificio = s.id_edificio')
        ->where(['s.estado' => 1])
        ->orderBy(['e.nombre' => SORT_ASC, 's.nombre' => SORT_ASC])
        ->asArray()
        ->all();

    if ($this->request->isPost) {
        $model->load($this->request->post());

        $idHorario = (int) $this->request->post('id_horario_ss', 0);
        $seleccionados = $this->request->post('sanitarios', []);

        $errores = [];

        if (empty($model->fecha_programada)) {
            $errores[] = 'Selecciona una fecha.';
        }
        if ($idHorario <= 0) {
            $errores[] = 'Selecciona un turno de servicio social.';
        }
        if (empty($seleccionados) || !is_array($seleccionados)) {
            $errores[] = 'Selecciona al menos un sanitario.';
        }

        $turno = $idHorario > 0 ? HorariosServicioSocial::findOne($idHorario) : null;
        if ($idHorario > 0 && !$turno) {
            $errores[] = 'El turno seleccionado no existe.';
        }

        // validar que el turno corresponda al día de la fecha
        if ($turno && !empty($model->fecha_programada)) {
            $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            $diaFecha = $dias[(int) date('w', strtotime($model->fecha_programada))];
            if ($turno->dia_semana !== $diaFecha) {
                $errores[] = "El turno seleccionado es para {$turno->dia_semana}, pero la fecha elegida es {$diaFecha}.";
            }
        }

        if (empty($errores) && $turno) {
            $creadas = 0;
            $duplicadas = 0;
            foreach ($seleccionados as $idSanitario) {
                $idSanitario = (int) $idSanitario;
                $existe = RondasProgramadas::find()
                    ->where([
                        'id_sanitario' => $idSanitario,
                        'id_horario_ss' => $idHorario,
                        'fecha_programada' => $model->fecha_programada,
                    ])
                    ->exists();
                if ($existe) {
                    $duplicadas++;
                    continue;
                }

                $r = new RondasProgramadas();
                $r->id_sanitario = $idSanitario;
                $r->id_horario_ss = $idHorario;
                $r->fecha_programada = $model->fecha_programada;
                $r->hora_programada = $turno->hora_inicio;
                $r->estado = $model->estado ?: 'Pendiente';

                if ($r->save(false)) {
                    $creadas++;
                }
            }

            $msg = "Rondas creadas: {$creadas}.";
            if ($duplicadas > 0) {
                $msg .= " Omitidas por duplicado: {$duplicadas}.";
            }
            Yii::$app->session->setFlash('success', $msg);
            return $this->redirect(['index']);
        }

        foreach ($errores as $e) {
            Yii::$app->session->setFlash('error', $e);
        }
    }

    return $this->render('create', [
        'model' => $model,
        'turnos' => $turnos,
        'sanitarios' => $sanitarios,
    ]);
}
    public function actionUpdate($id_ronda)
    {
        $model = $this->findModel($id_ronda);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Ronda actualizada correctamente.');
            return $this->redirect(['index']);
        }

        $turnos = HorariosServicioSocial::find()->where(['estado' => 1])->all();
        $sanitarios = Sanitarios::find()->where(['estado' => 1])->all();

        return $this->render('update', [
            'model' => $model,
            'turnos' => $turnos,
            'sanitarios' => $sanitarios,
        ]);
    }

    public function actionDelete($id_ronda)
    {
        $model = $this->findModel($id_ronda);
        $model->delete();
        Yii::$app->session->setFlash('success', 'Ronda eliminada.');
        return $this->redirect(['index']);
    }

    public function actionView($id_ronda)
    {
        $model = $this->findModel($id_ronda);
        return $this->render('view', ['model' => $model]);
    }

    protected function findModel($id_ronda)
    {
        if (($model = RondasProgramadas::findOne(['id_ronda' => $id_ronda])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('La ronda no existe.');
    }
}