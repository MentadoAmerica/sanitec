<?php

namespace app\controllers;

use Yii;
use app\models\PreguntasVerificacion;
use app\models\TiposReporte;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;

class PreguntasVerificacionController extends Controller
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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $preguntas = PreguntasVerificacion::find()
            ->orderBy(['categoria' => SORT_ASC, 'orden' => SORT_ASC, 'id_pregunta' => SORT_ASC])
            ->all();

        return $this->render('index', ['preguntas' => $preguntas]);
    }

    public function actionCreate()
    {
        $model = new PreguntasVerificacion();
        $model->obligatoria = 1;
        $model->estado = 1;
        $model->categoria = 'limpieza';
        $model->orden = (int) PreguntasVerificacion::find()->max('orden') + 1;

        if ($this->request->isPost) {
            $this->procesarPost($model);
            if (!$model->hasErrors() && $model->save(false)) {
                Yii::$app->session->setFlash('success', 'Pregunta creada correctamente.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('form', [
            'model' => $model,
            'tiposReporte' => $this->opcionesTiposReporte(),
            'esNuevo' => true,
        ]);
    }

    public function actionUpdate($id_pregunta)
    {
        $model = $this->findModel($id_pregunta);

        if ($this->request->isPost) {
            $this->procesarPost($model);
            if (!$model->hasErrors() && $model->save(false)) {
                Yii::$app->session->setFlash('success', 'Pregunta actualizada correctamente.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('form', [
            'model' => $model,
            'tiposReporte' => $this->opcionesTiposReporte(),
            'esNuevo' => false,
        ]);
    }

    public function actionDelete($id_pregunta)
    {
        $model = $this->findModel($id_pregunta);
        try {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Pregunta eliminada.');
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash('error',
                'No se puede eliminar porque ya tiene respuestas registradas. Puedes desactivarla.');
        }
        return $this->redirect(['index']);
    }

    private function procesarPost(PreguntasVerificacion $model): void
    {
        $post = $this->request->post('PreguntasVerificacion', []);

        $model->pregunta = trim((string) ($post['pregunta'] ?? ''));
        $model->categoria = (string) ($post['categoria'] ?? 'limpieza');
        $model->orden = (int) ($post['orden'] ?? 0);
        $model->obligatoria = !empty($post['obligatoria']) ? 1 : 0;
        $model->estado = !empty($post['estado']) ? 1 : 0;

        if ($model->pregunta === '') {
            $model->addError('pregunta', 'La pregunta es obligatoria.');
        }

        // leer opciones y triggers desde el JSON hidden
        $opcionesJson = (string) $this->request->post('opciones_json', '[]');
        $opcionesArr = json_decode($opcionesJson, true);

        if (!is_array($opcionesArr) || count($opcionesArr) < 1) {
            $model->addError('pregunta', 'Agrega al menos una opción.');
            return;
        }

        $opcionesFinales = [];
        $triggersFinales = [];
        $i = 1;
        foreach ($opcionesArr as $fila) {
            $texto = trim((string) ($fila['texto'] ?? ''));
            $trigger = trim((string) ($fila['trigger'] ?? ''));

            if ($texto === '') {
                continue;
            }
            $opcionesFinales[(string) $i] = $texto;
            if ($trigger !== '') {
                $triggersFinales[(string) $i] = [$trigger];
            }
            $i++;
        }

        if (empty($opcionesFinales)) {
            $model->addError('pregunta', 'Agrega al menos una opción con texto.');
            return;
        }

        $model->opciones_json = json_encode($opcionesFinales, JSON_UNESCAPED_UNICODE);
        $model->respuestas_reporte_json = json_encode($triggersFinales, JSON_UNESCAPED_UNICODE);
    }

    private function opcionesTiposReporte(): array
    {
        return TiposReporte::find()
            ->where(['estado' => 1])
            ->orderBy(['nombre' => SORT_ASC])
            ->select(['nombre'])
            ->column();
    }

    protected function findModel($id_pregunta)
    {
        if (($model = PreguntasVerificacion::findOne(['id_pregunta' => $id_pregunta])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('La pregunta no existe.');
    }
}