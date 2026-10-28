<?php

namespace app\controllers;

use Yii;
use app\models\AlumnoHorariosSs;
use app\models\AlumnoHorariosSsSearch;
use app\models\AlumnosServicioSocial;
use app\models\HorariosServicioSocial;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\IntegrityException;

class AlumnoHorariosSsController extends Controller
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
        $searchModel = new AlumnoHorariosSsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['id_alumno_horario' => SORT_DESC];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id_alumno_horario)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_alumno_horario),
        ]);
    }

    public function actionCreate()
    {
        $model = new AlumnoHorariosSs();
        $model->estado = 1;
        $model->fecha_inicio = date('Y-m-d');

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Inscripción registrada correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id_alumno_horario)
    {
        $model = $this->findModel($id_alumno_horario);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Inscripción actualizada correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id_alumno_horario)
    {
        $model = $this->findModel($id_alumno_horario);

        try {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Inscripción eliminada correctamente.');
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash(
                'error',
                'No se puede eliminar esta inscripción porque tiene registros asociados.'
            );
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id_alumno_horario)
    {
        if (($model = AlumnoHorariosSs::findOne(['id_alumno_horario' => $id_alumno_horario])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('La inscripción solicitada no existe.');
    }
}