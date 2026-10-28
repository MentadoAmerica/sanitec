<?php

namespace app\controllers;

use Yii;
use app\models\Reportes;
use app\models\ReportesSearch;
use app\models\HistorialReportes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
                        'matchCallback' => function () {
                            // admin o servicio social
                            $rol = (int) Yii::$app->user->identity->id_rol;
                            return in_array($rol, [1, 2], true);
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

        $estado = $this->request->get('estado');
        if (in_array($estado, ['Pendiente', 'En atención', 'Atendido', 'Cerrado'], true)) {
            $dataProvider->query->andWhere(['estado' => $estado]);
        }

        $conteos = [
            'todos' => (int) Reportes::find()->count(),
            'pendiente' => (int) Reportes::find()->where(['estado' => 'Pendiente'])->count(),
            'en_atencion' => (int) Reportes::find()->where(['estado' => 'En atención'])->count(),
            'atendido' => (int) Reportes::find()->where(['estado' => 'Atendido'])->count(),
            'cerrado' => (int) Reportes::find()->where(['estado' => 'Cerrado'])->count(),
        ];

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

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->id_usuario_reporta = Yii::$app->user->id;
            $model->fecha_hora_reporte = date('Y-m-d H:i:s');

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
        if (($model = Reportes::findOne(['id_reporte' => $id_reporte])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El reporte solicitado no existe.');
    }
}