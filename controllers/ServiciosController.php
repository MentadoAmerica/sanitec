<?php

namespace app\controllers;

use Yii;
use app\models\Servicios;
use app\models\ServiciosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class ServiciosController extends Controller
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
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ServiciosSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // ordenar por fecha descendente
        $dataProvider->sort->defaultOrder = ['fecha' => SORT_DESC, 'hora_checkin' => SORT_DESC];

        // filtro por estado (abierto / cerrado)
        $estado = $this->request->get('estado');
        if ($estado === 'abierto') {
            $dataProvider->query->andWhere(['hora_checkout' => null]);
        } elseif ($estado === 'cerrado') {
            $dataProvider->query->andWhere(['IS NOT', 'hora_checkout', null]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'estado' => $estado,
        ]);
    }

    public function actionView($id_servicio)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_servicio),
        ]);
    }

    public function actionUpdate($id_servicio)
    {
        $model = $this->findModel($id_servicio);
        $checkoutAnterior = $model->getOldAttribute('hora_checkout');

        if ($this->request->isPost) {
            $model->load($this->request->post());

            // nunca se permite modificar el check-in ni el check-out ya registrado
            $model->hora_checkin = $model->getOldAttribute('hora_checkin');

            if ($checkoutAnterior === null) {
                // servicio abierto: se puede cerrar manualmente
                if ($this->request->post('cerrar_manual') === '1') {
                    $horaCierre = $this->request->post('hora_cierre_manual');
                    if (!empty($horaCierre)) {
                        $model->hora_checkout = date('Y-m-d H:i:s', strtotime($horaCierre));
                        $nota = '[cerrado manualmente por '
                            . Yii::$app->user->identity->nombre
                            . ' ' . Yii::$app->user->identity->apellidos
                            . ' el ' . date('Y-m-d H:i') . ']';
                        $model->observaciones = trim(($model->observaciones ?? '') . ' ' . $nota);
                    }
                } else {
                    $model->hora_checkout = null;
                }
            } else {
                // servicio ya cerrado: no se puede cambiar el check-out
                $model->hora_checkout = $checkoutAnterior;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Servicio actualizado correctamente.');
                return $this->redirect(['view', 'id_servicio' => $model->id_servicio]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    protected function findModel($id_servicio)
    {
        if (($model = Servicios::findOne(['id_servicio' => $id_servicio])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El servicio solicitado no existe.');
    }
}