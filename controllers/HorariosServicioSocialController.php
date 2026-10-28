<?php

namespace app\controllers;

use Yii;
use app\models\HorariosServicioSocial;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class HorariosServicioSocialController extends Controller
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
        $horarios = HorariosServicioSocial::find()
            ->where(['estado' => 1])
            ->all();

        // agrupa por nombre de turno y por día
        $porTurno = [];
        $turnos = [];
        foreach ($horarios as $h) {
            $porTurno[$h->nombre_turno][$h->dia_semana] = $h;
            $turnos[$h->nombre_turno] = $h->nombre_turno;
        }
        ksort($turnos);

        return $this->render('index', [
            'porTurno' => $porTurno,
            'turnos' => array_values($turnos),
        ]);
    }

    public function actionView($id_horario_ss)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_horario_ss),
        ]);
    }

    public function actionCreate()
    {
        $model = new HorariosServicioSocial();

        if ($this->request->isGet) {
            $model->nombre_turno = $this->request->get('nombre_turno');
            $model->dia_semana = $this->request->get('dia_semana');
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Turno de servicio social registrado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id_horario_ss)
    {
        $model = $this->findModel($id_horario_ss);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Turno de servicio social actualizado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id_horario_ss)
    {
        $this->findModel($id_horario_ss)->delete();
        Yii::$app->session->setFlash('success', 'Turno de servicio social eliminado correctamente.');
        return $this->redirect(['index']);
    }

    protected function findModel($id_horario_ss)
    {
        if (($model = HorariosServicioSocial::findOne(['id_horario_ss' => $id_horario_ss])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El turno solicitado no existe.');
    }
}