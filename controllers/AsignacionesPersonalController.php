<?php

namespace app\controllers;

use app\models\AsignacionesPersonal;
use app\models\AsignacionesPersonalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AsignacionesPersonalController implements the CRUD actions for AsignacionesPersonal model.
 */
class AsignacionesPersonalController extends Controller
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
     * Lists all AsignacionesPersonal models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AsignacionesPersonalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AsignacionesPersonal model.
     * @param int $id_asignacion Id Asignacion
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_asignacion)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_asignacion),
        ]);
    }

    /**
     * Creates a new AsignacionesPersonal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AsignacionesPersonal();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_asignacion' => $model->id_asignacion]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AsignacionesPersonal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_asignacion Id Asignacion
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_asignacion)
    {
        $model = $this->findModel($id_asignacion);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_asignacion' => $model->id_asignacion]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AsignacionesPersonal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_asignacion Id Asignacion
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_asignacion)
    {
        $this->findModel($id_asignacion)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AsignacionesPersonal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_asignacion Id Asignacion
     * @return AsignacionesPersonal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_asignacion)
    {
        if (($model = AsignacionesPersonal::findOne(['id_asignacion' => $id_asignacion])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
