<?php

namespace app\controllers;

use Yii;
use app\models\AlumnosServicioSocial;
use app\models\User;
use app\models\Verificaciones;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class VerificacionesController extends Controller
{
    private const ASPECTOS = [
        'limpieza' => 'Limpieza general',
        'papel' => 'Disponibilidad de papel sanitario',
        'jabon' => 'Disponibilidad de jabón',
        'agua' => 'Disponibilidad de agua',
        'lavabos' => 'Estado de lavabos',
        'sanitarios' => 'Estado de sanitarios',
        'iluminacion' => 'Estado de iluminación',
        'condiciones' => 'Condiciones generales del área',
    ];

    private const ESTADOS_CHECKLIST = ['cumple', 'requiere_atencion', 'no_aplica'];

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
                            return $identity instanceof User && (int) $identity->id_rol === 2;
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => ['finalizar' => ['GET', 'POST']],
            ],
        ];
    }

    public function actionIndex()
    {
        $alumno = $this->getAlumno();
        $verificaciones = Yii::$app->db->createCommand(
            'SELECT v.id_verificacion, v.fecha_hora_inicio, v.fecha_hora_fin,
                    v.resultado_general, rp.fecha_programada, rp.hora_programada,
                    s.nombre AS sanitario_nombre, e.nombre AS edificio_nombre
             FROM {{%verificaciones}} v
             INNER JOIN {{%rondas_programadas}} rp ON rp.id_ronda = v.id_ronda
             INNER JOIN {{%sanitarios}} s ON s.id_sanitario = v.id_sanitario
             INNER JOIN {{%edificios}} e ON e.id_edificio = s.id_edificio
             WHERE v.id_alumno = :id_alumno
             ORDER BY v.fecha_hora_inicio DESC',
            [':id_alumno' => $alumno->id_alumno]
        )->queryAll();

        return $this->render('index', ['verificaciones' => $verificaciones]);
    }

    public function actionFinalizar($id_verificacion)
    {
        $alumno = $this->getAlumno();
        $model = $this->findOwnVerification($id_verificacion, $alumno->id_alumno);

        if ($model->fecha_hora_fin !== null) {
            return $this->redirect(['verificaciones/view', 'id_verificacion' => $model->id_verificacion]);
        }

        $erroresFormulario = [];
        $checklist = [];
        if ($this->request->isPost) {
            $checklist = $this->request->post('checklist', []);
            if (!is_array($checklist)) {
                $checklist = [];
            }

            foreach (self::ASPECTOS as $key => $label) {
                if (!isset($checklist[$key]) || !in_array($checklist[$key], self::ESTADOS_CHECKLIST, true)) {
                    $erroresFormulario[] = 'Indica el resultado de: ' . $label . '.';
                }
            }

            $archivo = UploadedFile::getInstanceByName('evidencia');
            $rutaEvidencia = $model->evidencia_foto;
            $archivoPendiente = null;
            if ($archivo !== null) {
                $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
                $extension = strtolower($archivo->extension);
                $tipoDetectado = $archivo->tempName !== '' ? (new \finfo(FILEINFO_MIME_TYPE))->file($archivo->tempName) : false;
                $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
                $imagen = $archivo->tempName !== '' ? @getimagesize($archivo->tempName) : false;

                if (
                    $archivo->error !== UPLOAD_ERR_OK
                    ||
                    !in_array($extension, $extensionesPermitidas, true)
                    || !in_array($tipoDetectado, $tiposPermitidos, true)
                    || $imagen === false
                    || $archivo->size > 5 * 1024 * 1024
                ) {
                    $erroresFormulario[] = 'La evidencia debe ser una imagen JPG, PNG o WEBP de máximo 5 MB.';
                } else {
                    $archivoPendiente = [
                        'archivo' => $archivo,
                        'extension' => $extension,
                        'nombre' => bin2hex(random_bytes(16)) . '.' . $extension,
                    ];
                }
            }

            $observaciones = trim((string) $this->request->post('observaciones', ''));
            if ($erroresFormulario === []) {
                $model->checklist_json = json_encode($checklist, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
                $model->observaciones = $observaciones !== '' ? $observaciones : null;
                $model->evidencia_foto = $rutaEvidencia;
                $model->resultado_general = in_array('requiere_atencion', $checklist, true)
                    ? 'Requiere atención'
                    : 'Conforme';
                $model->fecha_hora_fin = date('Y-m-d H:i:s');

                if ($archivoPendiente !== null) {
                    $rutaEvidencia = Yii::$app->request->baseUrl . '/uploads/verificaciones/' . $archivoPendiente['nombre'];
                    $model->evidencia_foto = $rutaEvidencia;
                }

                if ($model->validate()) {
                    $rutaFisica = null;
                    if ($archivoPendiente !== null) {
                        $directorio = Yii::getAlias('@webroot/uploads/verificaciones');
                        FileHelper::createDirectory($directorio);
                        $rutaFisica = $directorio . DIRECTORY_SEPARATOR . $archivoPendiente['nombre'];
                        if (!$archivoPendiente['archivo']->saveAs($rutaFisica, false)) {
                            throw new ServerErrorHttpException('No se pudo guardar la fotografía de evidencia.');
                        }
                    }

                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        if (!$model->save(false)) {
                            throw new \RuntimeException('No se pudo guardar el resultado de la verificación.');
                        }
                        Yii::$app->db->createCommand(
                            'UPDATE {{%rondas_programadas}}
                             SET estado = :estado, fecha_hora_fin = :fecha_fin
                             WHERE id_ronda = :id_ronda',
                            [
                                ':estado' => 'Completada',
                                ':fecha_fin' => $model->fecha_hora_fin,
                                ':id_ronda' => $model->id_ronda,
                            ]
                        )->execute();
                        $transaction->commit();

                        Yii::$app->session->setFlash('success', 'La ronda se guardó correctamente.');
                        return $this->redirect(['view', 'id_verificacion' => $model->id_verificacion]);
                    } catch (\Throwable $exception) {
                        if ($transaction->isActive) {
                            $transaction->rollBack();
                        }
                        if ($rutaFisica !== null && is_file($rutaFisica)) {
                            unlink($rutaFisica);
                        }
                        throw $exception;
                    }
                }

                $erroresFormulario = array_merge($erroresFormulario, $model->getFirstErrors());
            }
        }

        return $this->render('finalizar', [
            'model' => $model,
            'checklist' => $checklist,
            'aspectos' => self::ASPECTOS,
            'erroresFormulario' => $erroresFormulario,
        ]);
    }

    public function actionView($id_verificacion)
    {
        $alumno = $this->getAlumno();
        $model = $this->findOwnVerification($id_verificacion, $alumno->id_alumno);
        $checklist = $model->checklist_json !== null
            ? json_decode($model->checklist_json, true, 512, JSON_THROW_ON_ERROR)
            : [];

        return $this->render('view', [
            'model' => $model,
            'checklist' => $checklist,
            'aspectos' => self::ASPECTOS,
        ]);
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

    private function findOwnVerification($id, $idAlumno): Verificaciones
    {
        $model = Verificaciones::findOne([
            'id_verificacion' => $id,
            'id_alumno' => $idAlumno,
        ]);
        if ($model === null) {
            throw new NotFoundHttpException('La verificación solicitada no existe.');
        }
        return $model;
    }
}
