<?php

namespace app\controllers;

use app\models\Edificios;
use app\models\EdificiosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EdificiosController implements the CRUD actions for Edificios model.
 */
class EdificiosController extends Controller
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
     * Lists all Edificios models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EdificiosSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Edificios model.
     * @param int $id_edificio Id Edificio
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_edificio)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_edificio),
        ]);
    }

    /**
     * Creates a new Edificios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Edificios();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_edificio' => $model->id_edificio]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Edificios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_edificio Id Edificio
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_edificio)
    {
        $model = $this->findModel($id_edificio);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_edificio' => $model->id_edificio]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Edificios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_edificio Id Edificio
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_edificio)
    {
        $this->findModel($id_edificio)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Edificios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_edificio Id Edificio
     * @return Edificios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_edificio)
    {
        if (($model = Edificios::findOne(['id_edificio' => $id_edificio])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
