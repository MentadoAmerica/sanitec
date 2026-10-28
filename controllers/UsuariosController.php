<?php

namespace app\controllers;

use Yii;
use app\models\Usuarios;
use app\models\UsuariosSearch;
use app\models\Roles;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class UsuariosController extends Controller
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
        $searchModel = new UsuariosSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id_usuario)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_usuario),
        ]);
    }

    public function actionCreate()
    {
        $model = new Usuarios();
        $model->scenario = 'create';

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Usuario registrado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id_usuario)
    {
        $model = $this->findModel($id_usuario);
        $hashAnterior = $model->contrasena;

        if ($this->request->isPost && $model->load($this->request->post())) {
            // limpia el campo para que beforeSave decida si hashea o conserva
            if (empty($model->contrasena)) {
                $model->contrasena = null;
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Usuario actualizado correctamente.');
                return $this->redirect(['index']);
            }
        }

        // en el formulario no se muestra el hash actual
        $model->contrasena = '';
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id_usuario)
    {
        $model = $this->findModel($id_usuario);

        // no permitir borrarse a sí mismo
        if ((int) $model->id_usuario === (int) Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', 'No puedes eliminar tu propia cuenta.');
            return $this->redirect(['index']);
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'Usuario eliminado correctamente.');
        return $this->redirect(['index']);
    }

    protected function findModel($id_usuario)
    {
        if (($model = Usuarios::findOne(['id_usuario' => $id_usuario])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El usuario solicitado no existe.');
    }
}