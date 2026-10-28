<?php

namespace app\controllers;

use Yii;
use app\models\HorariosPersonal;
use app\models\Personal;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class HorariosPersonalController extends Controller
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
        $personalList = Personal::find()
            ->where(['estado' => 1])
            ->orderBy(['id_personal' => SORT_ASC])
            ->all();

        $horarios = HorariosPersonal::find()
            ->where(['estado' => 1])
            ->all();

        $porPersonal = [];
        foreach ($horarios as $h) {
            $porPersonal[$h->id_personal][$h->dia_semana] = $h;
        }

        return $this->render('index', [
            'personalList' => $personalList,
            'porPersonal' => $porPersonal,
        ]);
    }

    public function actionView($id_horario)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_horario),
        ]);
    }

    public function actionCreate()
    {
        $model = new HorariosPersonal();

        // permite precargar desde la celda "+" de la vista semanal
        if ($this->request->isGet) {
            $model->id_personal = $this->request->get('id_personal');
            $model->dia_semana = $this->request->get('dia_semana');
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Horario registrado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id_horario)
    {
        $model = $this->findModel($id_horario);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Horario actualizado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id_horario)
    {
        $this->findModel($id_horario)->delete();
        Yii::$app->session->setFlash('success', 'Horario eliminado correctamente.');
        return $this->redirect(['index']);
    }

    protected function findModel($id_horario)
    {
        if (($model = HorariosPersonal::findOne(['id_horario' => $id_horario])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El horario solicitado no existe.');
    }
}