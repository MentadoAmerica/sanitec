<?php

namespace app\controllers;

use Yii;
use app\models\AlumnosServicioSocial;
use app\models\AlumnosServicioSocialSearch;
use app\models\Usuarios;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\IntegrityException;

class AlumnosServicioSocialController extends Controller
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
        $searchModel = new AlumnosServicioSocialSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['id_alumno' => SORT_ASC];

        $totalActivos = (int) AlumnosServicioSocial::find()->where(['estado' => 1])->count();
        $totalInactivos = (int) AlumnosServicioSocial::find()->where(['estado' => 0])->count();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalActivos' => $totalActivos,
            'totalInactivos' => $totalInactivos,
        ]);
    }

    public function actionView($id_alumno)
    {
        $model = $this->findModel($id_alumno);

        $turnos = Yii::$app->db->createCommand(
            'SELECT ahs.*, hss.nombre_turno, hss.dia_semana, hss.hora_inicio, hss.hora_fin
             FROM {{%alumno_horarios_ss}} ahs
             INNER JOIN {{%horarios_servicio_social}} hss
                 ON hss.id_horario_ss = ahs.id_horario_ss
             WHERE ahs.id_alumno = :id AND ahs.estado = 1
             ORDER BY hss.dia_semana ASC, hss.hora_inicio ASC',
            [':id' => $id_alumno]
        )->queryAll();

        $verificaciones = (int) Yii::$app->db->createCommand(
            'SELECT COUNT(*) FROM {{%verificaciones}} WHERE id_alumno = :id',
            [':id' => $id_alumno]
        )->queryScalar();

        return $this->render('view', [
            'model' => $model,
            'turnos' => $turnos,
            'verificaciones' => $verificaciones,
        ]);
    }

    public function actionCreate()
    {
        $model = new AlumnosServicioSocial();
        $model->estado = 1;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Alumno de servicio social registrado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id_alumno)
    {
        $model = $this->findModel($id_alumno);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Alumno de servicio social actualizado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id_alumno)
    {
        $model = $this->findModel($id_alumno);

        try {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Alumno eliminado del programa de servicio social.');
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash(
                'error',
                'No se puede eliminar porque tiene verificaciones u otros registros asociados. Puedes marcarlo como inactivo.'
            );
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id_alumno)
    {
        if (($model = AlumnosServicioSocial::findOne(['id_alumno' => $id_alumno])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El alumno solicitado no existe.');
    }
}