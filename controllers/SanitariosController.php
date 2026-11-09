<?php

namespace app\controllers;

use Yii;
use app\models\Sanitarios;
use app\models\SanitariosSearch;
use app\models\Edificios;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;

class SanitariosController extends Controller
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
        $searchModel = new SanitariosSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id_sanitario)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_sanitario),
        ]);
    }

    public function actionGenerateQr($id_sanitario)
    {
        $model = $this->findModel($id_sanitario);

        if (trim((string) $model->codigo_qr) === '') {
            $model->codigo_qr = Sanitarios::generateUniqueQrToken();
            $model->save(false);
        }

        return $this->render('qr', [
            'model' => $model,
            'qrUrl' => urlencode($model->codigo_qr),
            'surveyUrl' => \yii\helpers\Url::to(['/encuestas/qr', 'token' => $model->codigo_qr], true),
        ]);
    }

    public function actionDownloadQr($id_sanitario): Response
    {
        $model = $this->findModel($id_sanitario);
        if (trim((string) $model->codigo_qr) === '') {
            $model->codigo_qr = Sanitarios::generateUniqueQrToken();
            $model->save(false);
        }

        $image = (new QRCode(new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'version' => 7,
            'eccLevel' => QRCode::ECC_L,
            'scale' => 8,
        ])))->render($model->codigo_qr);

        return Yii::$app->response->sendContentAsFile(
            $image,
            'qr-' . $model->id_sanitario . '.png',
            ['mimeType' => 'image/png']
        );
    }

    public function actionCreate()
    {
        $model = new Sanitarios();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Sanitario registrado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id_sanitario)
    {
        $model = $this->findModel($id_sanitario);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Sanitario actualizado correctamente.');
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id_sanitario)
    {
        $this->findModel($id_sanitario)->delete();
        Yii::$app->session->setFlash('success', 'Sanitario eliminado correctamente.');
        return $this->redirect(['index']);
    }

    protected function findModel($id_sanitario)
    {
        if (($model = Sanitarios::findOne(['id_sanitario' => $id_sanitario])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El sanitario solicitado no existe.');
    }
}