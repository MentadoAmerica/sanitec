<?php

namespace app\controllers;

use Yii;
use app\models\Reportes;
use app\models\ReportesSearch;
use app\models\HistorialReportes;
use app\models\Verificaciones;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class ReportesController extends Controller
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
                        'actions' => ['index', 'view', 'create'],
                        'matchCallback' => function () {
                            $identity = Yii::$app->user->identity;
                            return $identity instanceof User
                                && in_array((int) $identity->id_rol, [1, 2], true);
                        },
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['update', 'cambiar-estado'],
                        'matchCallback' => static function () {
                            $identity = Yii::$app->user->identity;
                            return $identity instanceof User && (int) $identity->id_rol === 1;
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'cambiar-estado' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ReportesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['fecha_hora_reporte' => SORT_DESC];
        $identity = $this->getIdentity();
        $esServicioSocial = (int) $identity->id_rol === 2;
        if ($esServicioSocial) {
            $dataProvider->query->andWhere(['id_usuario_reporta' => $identity->getId()]);
        }

        $estado = $this->request->get('estado');
        if (in_array($estado, ['Pendiente', 'En atención', 'Atendido', 'Cerrado'], true)) {
            $dataProvider->query->andWhere(['estado' => $estado]);
        }

        $queryConteos = Reportes::find();
        if ($esServicioSocial) {
            $queryConteos->andWhere(['id_usuario_reporta' => $identity->getId()]);
        }
        $conteos = ['todos' => (int) (clone $queryConteos)->count()];
        foreach ([
            'pendiente' => 'Pendiente',
            'en_atencion' => 'En atención',
            'atendido' => 'Atendido',
            'cerrado' => 'Cerrado',
        ] as $key => $estadoConteo) {
            $conteos[$key] = (int) (clone $queryConteos)->andWhere(['estado' => $estadoConteo])->count();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'estado' => $estado,
            'conteos' => $conteos,
        ]);
    }

    public function actionView($id_reporte)
    {
        $model = $this->findModel($id_reporte);
        $historial = HistorialReportes::find()
            ->where(['id_reporte' => $id_reporte])
            ->orderBy(['fecha_hora' => SORT_DESC])
            ->all();

        return $this->render('view', [
            'model' => $model,
            'historial' => $historial,
        ]);
    }

    public function actionCreate()
    {
        $model = new Reportes();
        $model->estado = 'Pendiente';
        $idVerificacion = $this->request->get('id_verificacion');
        $identity = $this->getIdentity();
        $esServicioSocial = (int) $identity->id_rol === 2;
        if ($esServicioSocial && empty($idVerificacion)) {
            throw new ForbiddenHttpException('Genera el reporte desde una verificación finalizada.');
        }

        $verificacion = null;
        if (!empty($idVerificacion)) {
            $queryVerificacion = Verificaciones::find()->where([
                'id_verificacion' => $idVerificacion,
            ]);
            if ($esServicioSocial) {
                $alumno = \app\models\AlumnosServicioSocial::findOne([
                    'id_usuario' => $identity->getId(),
                    'estado' => 1,
                ]);
                if ($alumno === null) {
                    throw new ForbiddenHttpException('Tu cuenta no está vinculada a un alumno activo de servicio social.');
                }
                $queryVerificacion->andWhere([
                    'id_alumno' => $alumno->id_alumno,
                ])->andWhere(['IS NOT', 'fecha_hora_fin', null]);
            }
            $verificacion = $queryVerificacion->one();
            if ($verificacion === null) {
                throw new NotFoundHttpException('La verificación no existe o no puede generar reportes.');
            }
            $model->id_sanitario = $verificacion->id_sanitario;
            $model->evidencia_foto = $verificacion->evidencia_foto;
        }

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->id_usuario_reporta = $identity->getId();
            $model->fecha_hora_reporte = date('Y-m-d H:i:s');
            if ($verificacion !== null) {
                $model->id_verificacion = $verificacion->id_verificacion;
                $model->id_sanitario = $verificacion->id_sanitario;
                if ($esServicioSocial) {
                    $model->evidencia_foto = $verificacion->evidencia_foto;
                }
            }

            if ($model->save()) {
                $this->registrarHistorial($model->id_reporte, null, 'Pendiente', 'reporte creado');
                Yii::$app->session->setFlash('success', 'Reporte registrado correctamente.');
                return $this->redirect(['view', 'id_reporte' => $model->id_reporte]);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id_reporte)
    {
        $model = $this->findModel($id_reporte);

        if ($model->estado === 'Cerrado') {
            Yii::$app->session->setFlash('error', 'Un reporte cerrado no se puede editar.');
            return $this->redirect(['view', 'id_reporte' => $model->id_reporte]);
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Reporte actualizado correctamente.');
            return $this->redirect(['view', 'id_reporte' => $model->id_reporte]);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionCambiarEstado($id_reporte)
    {
        $model = $this->findModel($id_reporte);
        $nuevo = (string) $this->request->post('nuevo_estado');
        $observacion = trim((string) $this->request->post('observacion'));

        if (!in_array($nuevo, $model->getSiguientesEstados(), true)) {
            Yii::$app->session->setFlash('error', 'Transición de estado no permitida.');
            return $this->redirect(['view', 'id_reporte' => $model->id_reporte]);
        }

        $anterior = $model->estado;
        $model->estado = $nuevo;
        if ($nuevo === 'Cerrado') {
            $model->fecha_cierre = date('Y-m-d H:i:s');
        }

        if ($model->save()) {
            $this->registrarHistorial($model->id_reporte, $anterior, $nuevo, $observacion);
            Yii::$app->session->setFlash('success', 'Estado actualizado a "' . $nuevo . '".');
        }

        return $this->redirect(['view', 'id_reporte' => $model->id_reporte]);
    }

    protected function registrarHistorial($id_reporte, $anterior, $nuevo, $observacion)
    {
        $h = new HistorialReportes();
        $h->id_reporte = $id_reporte;
        $h->id_usuario = Yii::$app->user->id;
        $h->estado_anterior = $anterior;
        $h->estado_nuevo = $nuevo;
        $h->fecha_hora = date('Y-m-d H:i:s');
        $h->observaciones = $observacion !== '' ? $observacion : null;
        $h->save(false);
    }

    protected function findModel($id_reporte)
    {
        $query = Reportes::find()->where(['id_reporte' => $id_reporte]);
        $identity = $this->getIdentity();
        if ((int) $identity->id_rol === 2) {
            $query->andWhere(['id_usuario_reporta' => $identity->getId()]);
        }
        if (($model = $query->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El reporte solicitado no existe.');
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