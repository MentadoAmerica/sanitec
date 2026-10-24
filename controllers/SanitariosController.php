<?php

namespace app\controllers;

use app\models\Sanitarios;
use app\models\SanitariosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SanitariosController implements the CRUD actions for Sanitarios model.
 */
class SanitariosController extends Controller
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
     * Lists all Sanitarios models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SanitariosSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sanitarios model.
     * @param int $id_sanitario Id Sanitario
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_sanitario)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_sanitario),
        ]);
    }

    /**
     * Creates a new Sanitarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Sanitarios();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_sanitario' => $model->id_sanitario]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Sanitarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_sanitario Id Sanitario
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_sanitario)
    {
        $model = $this->findModel($id_sanitario);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_sanitario' => $model->id_sanitario]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Sanitarios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_sanitario Id Sanitario
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_sanitario)
    {
        $this->findModel($id_sanitario)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sanitarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_sanitario Id Sanitario
     * @return Sanitarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_sanitario)
    {
        if (($model = Sanitarios::findOne(['id_sanitario' => $id_sanitario])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
