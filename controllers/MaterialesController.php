<?php

namespace app\controllers;

use app\models\Materiales;
use app\models\MaterialesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MaterialesController implements the CRUD actions for Materiales model.
 */
class MaterialesController extends Controller
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
     * Lists all Materiales models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MaterialesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Materiales model.
     * @param int $id_material Id Material
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_material)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_material),
        ]);
    }

    /**
     * Creates a new Materiales model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Materiales();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_material' => $model->id_material]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Materiales model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_material Id Material
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_material)
    {
        $model = $this->findModel($id_material);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_material' => $model->id_material]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Materiales model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_material Id Material
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_material)
    {
        $this->findModel($id_material)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Materiales model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_material Id Material
     * @return Materiales the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_material)
    {
        if (($model = Materiales::findOne(['id_material' => $id_material])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
