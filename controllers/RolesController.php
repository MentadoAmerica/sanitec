<?php

namespace app\controllers;

use Yii;
use app\models\Roles;
use app\models\RolesSearch;
use app\models\Usuarios;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class RolesController extends Controller
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
        $searchModel = new RolesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id_rol)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_rol),
        ]);
    }

    public function actionCreate()
    {
        $model = new Roles();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Rol registrado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id_rol)
    {
        $model = $this->findModel($id_rol);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Rol actualizado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id_rol)
    {
        $model = $this->findModel($id_rol);

        // no se puede borrar si hay usuarios con este rol
        if (Usuarios::find()->where(['id_rol' => $id_rol])->exists()) {
            Yii::$app->session->setFlash(
                'error',
                'No se puede eliminar el rol porque tiene usuarios asignados. Puedes marcarlo como inactivo.'
            );
            return $this->redirect(['index']);
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'Rol eliminado correctamente.');
        return $this->redirect(['index']);
    }

    protected function findModel($id_rol)
    {
        if (($model = Roles::findOne(['id_rol' => $id_rol])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El rol solicitado no existe.');
    }
}