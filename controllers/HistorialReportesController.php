<?php

namespace app\controllers;

use app\models\HistorialReportes;
use app\models\HistorialReportesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HistorialReportesController implements the CRUD actions for HistorialReportes model.
 */
class HistorialReportesController extends Controller
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
     * Lists all HistorialReportes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new HistorialReportesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HistorialReportes model.
     * @param int $id_historial Id Historial
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_historial)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_historial),
        ]);
    }

    /**
     * Creates a new HistorialReportes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new HistorialReportes();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_historial' => $model->id_historial]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing HistorialReportes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_historial Id Historial
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_historial)
    {
        $model = $this->findModel($id_historial);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_historial' => $model->id_historial]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HistorialReportes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_historial Id Historial
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_historial)
    {
        $this->findModel($id_historial)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the HistorialReportes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_historial Id Historial
     * @return HistorialReportes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_historial)
    {
        if (($model = HistorialReportes::findOne(['id_historial' => $id_historial])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
