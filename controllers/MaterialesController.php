<?php

namespace app\controllers;

use Yii;
use app\models\Materiales;
use app\models\MaterialesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\IntegrityException;

class MaterialesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    // admin: acceso total
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return (int) Yii::$app->user->identity->id_rol === 1;
                        },
                    ],
                    // almacén: solo consulta
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return (int) Yii::$app->user->identity->id_rol === 4;
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
        $searchModel = new MaterialesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['nombre' => SORT_ASC];

        $totalActivos = (int) Materiales::find()->where(['estado' => 1])->count();
        $totalBajos = (int) Materiales::find()
            ->where(['estado' => 1])
            ->andWhere('[[stock_actual]] <= [[stock_minimo]]')
            ->count();
        $valorInventario = (float) Materiales::find()
            ->where(['estado' => 1])
            ->sum('[[stock_actual]] * [[costo_unitario]]');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalActivos' => $totalActivos,
            'totalBajos' => $totalBajos,
            'valorInventario' => $valorInventario,
        ]);
    }

    public function actionView($id_material)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_material),
        ]);
    }

    public function actionCreate()
    {
        $model = new Materiales();
        $model->estado = 1;
        $model->stock_actual = 0;
        $model->stock_minimo = 0;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Material registrado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id_material)
    {
        $model = $this->findModel($id_material);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Material actualizado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id_material)
    {
        $model = $this->findModel($id_material);

        try {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Material eliminado correctamente.');
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash(
                'error',
                'No se puede eliminar porque tiene movimientos registrados. Puedes marcarlo como inactivo.'
            );
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id_material)
    {
        if (($model = Materiales::findOne(['id_material' => $id_material])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El material solicitado no existe.');
    }
}