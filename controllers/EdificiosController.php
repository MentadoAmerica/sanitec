<?php

namespace app\controllers;

use Yii;
use app\models\Edificios;
use app\models\EdificiosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class EdificiosController extends Controller
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
        $searchModel = new EdificiosSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id_edificio)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_edificio),
        ]);
    }

    public function actionCreate()
    {
        $model = new Edificios();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Edificio registrado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id_edificio)
    {
        $model = $this->findModel($id_edificio);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Edificio actualizado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id_edificio)
    {
        $this->findModel($id_edificio)->delete();
        Yii::$app->session->setFlash('success', 'Edificio eliminado correctamente.');
        return $this->redirect(['index']);
    }

    protected function findModel($id_edificio)
    {
        if (($model = Edificios::findOne(['id_edificio' => $id_edificio])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El edificio solicitado no existe.');
    }
}