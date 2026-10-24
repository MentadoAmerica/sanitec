<?php

namespace app\controllers;

use app\models\TiposReporte;
use app\models\TiposReporteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TiposReporteController implements the CRUD actions for TiposReporte model.
 */
class TiposReporteController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all TiposReporte models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TiposReporteSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TiposReporte model.
     * @param int $id_tipo_reporte Id Tipo Reporte
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_tipo_reporte)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_tipo_reporte),
        ]);
    }

    /**
     * Creates a new TiposReporte model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TiposReporte();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_tipo_reporte' => $model->id_tipo_reporte]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TiposReporte model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_tipo_reporte Id Tipo Reporte
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_tipo_reporte)
    {
        $model = $this->findModel($id_tipo_reporte);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_tipo_reporte' => $model->id_tipo_reporte]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TiposReporte model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_tipo_reporte Id Tipo Reporte
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_tipo_reporte)
    {
        $this->findModel($id_tipo_reporte)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TiposReporte model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_tipo_reporte Id Tipo Reporte
     * @return TiposReporte the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_tipo_reporte)
    {
        if (($model = TiposReporte::findOne(['id_tipo_reporte' => $id_tipo_reporte])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
