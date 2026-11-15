<?php

namespace app\controllers;

use app\models\RespuestasVerificacion;
use app\models\RespuestasVerificacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RespuestasVerificacionController implements the CRUD actions for RespuestasVerificacion model.
 */
class RespuestasVerificacionController extends Controller
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
     * Lists all RespuestasVerificacion models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RespuestasVerificacionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RespuestasVerificacion model.
     * @param int $id_respuesta Id Respuesta
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_respuesta)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_respuesta),
        ]);
    }

    /**
     * Creates a new RespuestasVerificacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new RespuestasVerificacion();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_respuesta' => $model->id_respuesta]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RespuestasVerificacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_respuesta Id Respuesta
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_respuesta)
    {
        $model = $this->findModel($id_respuesta);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_respuesta' => $model->id_respuesta]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RespuestasVerificacion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_respuesta Id Respuesta
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_respuesta)
    {
        $this->findModel($id_respuesta)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RespuestasVerificacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_respuesta Id Respuesta
     * @return RespuestasVerificacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_respuesta)
    {
        if (($model = RespuestasVerificacion::findOne(['id_respuesta' => $id_respuesta])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
