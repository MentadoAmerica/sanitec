<?php

namespace app\controllers;

use Yii;
use app\models\Materiales;
use app\models\Reportes;
use app\models\SolicitudesAbastecimiento;
use app\models\Verificaciones;
use app\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class SolicitudesAbastecimientoController extends Controller
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
                        'actions' => ['index'],
                        'matchCallback' => static function () {
                            $identity = Yii::$app->user->identity;
                            return $identity instanceof User
                                && in_array((int) $identity->id_rol, [1, 2, 4], true);
                        },
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['create'],
                        'matchCallback' => static function () {
                            $identity = Yii::$app->user->identity;
                            return $identity instanceof User
                                && in_array((int) $identity->id_rol, [1, 2], true);
                        },
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['cambiar-estado'],
                        'matchCallback' => static function () {
                            $identity = Yii::$app->user->identity;
                            return $identity instanceof User
                                && in_array((int) $identity->id_rol, [1, 4], true);
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => ['cambiar-estado' => ['POST']],
            ],
        ];
    }

    public function actionIndex()
    {
        $query = SolicitudesAbastecimiento::find()
            ->with(['reporte', 'material', 'usuarioSolicita'])
            ->orderBy(['fecha_solicitud' => SORT_DESC]);
        $identity = $this->getIdentity();
        $esServicioSocial = (int) $identity->id_rol === 2;
        if ($esServicioSocial) {
            $query->andWhere(['id_usuario_solicita' => $identity->getId()]);
        }

        return $this->render('index', [
            'solicitudes' => $query->all(),
            'puedeActualizar' => in_array((int) $identity->id_rol, [1, 4], true),
        ]);
    }

    public function actionCreate($id_reporte)
    {
        $reporte = Reportes::findOne(['id_reporte' => $id_reporte]);
        if ($reporte === null) {
            throw new NotFoundHttpException('El reporte indicado no existe.');
        }

        $identity = $this->getIdentity();
        $rol = (int) $identity->id_rol;
        if (
            $rol === 2
            && (
                (int) $reporte->id_usuario_reporta !== (int) $identity->getId()
                || $reporte->id_verificacion === null
            )
        ) {
            throw new ForbiddenHttpException('Solo puedes solicitar insumos para un reporte propio vinculado a una verificación.');
        }
        if ($rol === 2 && Verificaciones::find()->where([
            'id_verificacion' => $reporte->id_verificacion,
            'id_alumno' => $this->getAlumnoId(),
        ])->andWhere(['IS NOT', 'fecha_hora_fin', null])->exists() === false) {
            throw new ForbiddenHttpException('La verificación vinculada al reporte debe estar finalizada.');
        }

        $model = new SolicitudesAbastecimiento();
        $model->id_reporte = (int) $reporte->id_reporte;
        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->id_reporte = (int) $reporte->id_reporte;
            $model->id_usuario_solicita = (int) $identity->getId();
            $model->fecha_solicitud = date('Y-m-d H:i:s');
            $model->estado = 'Pendiente';
            $material = Materiales::findOne(['id_material' => $model->id_material, 'estado' => 1]);
            if ($material === null) {
                $model->addError('id_material', 'Selecciona un material activo.');
            } elseif ($model->save()) {
                Yii::$app->session->setFlash('success', 'La solicitud de insumos se registró correctamente.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'reporte' => $reporte,
            'materiales' => Materiales::find()->where(['estado' => 1])->orderBy(['nombre' => SORT_ASC])->all(),
        ]);
    }

    public function actionCambiarEstado($id_solicitud)
    {
        $model = $this->findModel($id_solicitud);
        $nuevoEstado = (string) $this->request->post('nuevo_estado');
        $transiciones = [
            'Pendiente' => ['En atención', 'Rechazada'],
            'En atención' => ['Atendida', 'Rechazada'],
        ];
        if (!in_array($nuevoEstado, $transiciones[$model->estado] ?? [], true)) {
            Yii::$app->session->setFlash('error', 'El cambio de estado solicitado no es válido.');
            return $this->redirect(['index']);
        }

        $model->estado = $nuevoEstado;
        if (!$model->save()) {
            Yii::$app->session->setFlash('error', 'No se pudo actualizar la solicitud.');
            return $this->redirect(['index']);
        }

        Yii::$app->session->setFlash('success', 'Estado de solicitud actualizado.');
        return $this->redirect(['index']);
    }

    private function findModel($id): SolicitudesAbastecimiento
    {
        $model = SolicitudesAbastecimiento::findOne(['id_solicitud' => $id]);
        if ($model === null) {
            throw new NotFoundHttpException('La solicitud de insumos no existe.');
        }
        return $model;
    }

    private function getAlumnoId(): int
    {
        return (int) Yii::$app->db->createCommand(
            'SELECT id_alumno FROM {{%alumnos_servicio_social}}
             WHERE id_usuario = :id_usuario AND estado = 1',
            [':id_usuario' => $this->getIdentity()->getId()]
        )->queryScalar();
    }

    private function getIdentity(): User
    {
        $identity = Yii::$app->user->identity;
        if (!$identity instanceof User) {
            throw new ForbiddenHttpException('La sesión actual no corresponde a una cuenta válida.');
        }
        return $identity;
    }
}
