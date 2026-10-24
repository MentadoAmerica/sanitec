<?php

namespace app\controllers;

use app\models\HorariosPersonal;
use app\models\HorariosPersonalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HorariosPersonalController implements the CRUD actions for HorariosPersonal model.
 */
class HorariosPersonalController extends Controller
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
     * Lists all HorariosPersonal models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new HorariosPersonalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HorariosPersonal model.
     * @param int $id_horario Id Horario
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_horario)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_horario),
        ]);
    }

    /**
     * Creates a new HorariosPersonal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new HorariosPersonal();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_horario' => $model->id_horario]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing HorariosPersonal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_horario Id Horario
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_horario)
    {
        $model = $this->findModel($id_horario);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_horario' => $model->id_horario]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HorariosPersonal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_horario Id Horario
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_horario)
    {
        $this->findModel($id_horario)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the HorariosPersonal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_horario Id Horario
     * @return HorariosPersonal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_horario)
    {
        if (($model = HorariosPersonal::findOne(['id_horario' => $id_horario])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
