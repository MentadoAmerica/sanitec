<?php

namespace app\controllers;

use Yii;
use app\models\TiposReporte;
use app\models\TiposReporteSearch;
use app\models\Reportes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\IntegrityException;

class TiposReporteController extends Controller
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
                            // solo admin
                            return (int) Yii::$app->user->identity->id_rol === 1;
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new TiposReporteSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new TiposReporte();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Tipo de reporte registrado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id_tipo_reporte)
    {
        $model = $this->findModel($id_tipo_reporte);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Tipo de reporte actualizado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id_tipo_reporte)
    {
        $model = $this->findModel($id_tipo_reporte);

        try {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Tipo de reporte eliminado correctamente.');
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash(
                'error',
                'No se puede eliminar este tipo de reporte porque tiene reportes asociados. Puedes marcarlo como inactivo.'
            );
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id_tipo_reporte)
    {
        if (($model = TiposReporte::findOne(['id_tipo_reporte' => $id_tipo_reporte])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El tipo de reporte solicitado no existe.');
    }
}