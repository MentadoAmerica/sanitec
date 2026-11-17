<?php

namespace app\controllers;

use Yii;
use app\models\EvaluacionesSatisfaccion;
use app\models\PreguntasSatisfaccion;
use app\models\User;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class EvaluacionesSatisfaccionController extends Controller
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
                            return Yii::$app->user->identity instanceof User
                                && (int) Yii::$app->user->identity->id_rol === 1;
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['GET'],
                    'preguntas' => ['GET'],
                    'editar-pregunta' => ['GET', 'POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $query = EvaluacionesSatisfaccion::find()
            ->select(['evaluaciones_satisfaccion.*', 's.nombre AS sanitario_nombre', 'e.nombre AS edificio_nombre'])
            ->leftJoin('{{%sanitarios}} s', 's.id_sanitario = evaluaciones_satisfaccion.id_sanitario')
            ->leftJoin('{{%edificios}} e', 'e.id_edificio = s.id_edificio')
            ->orderBy(['fecha_hora' => SORT_DESC]);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->asArray()->all(),
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPreguntas(): string
    {
        return $this->render('preguntas', [
            'preguntas' => PreguntasSatisfaccion::find()->orderBy(['id_pregunta' => SORT_ASC])->all(),
        ]);
    }

    public function actionEditarPregunta(int $id): Response|string
{
    $model = PreguntasSatisfaccion::findOne($id);
    if ($model === null) {
        throw new NotFoundHttpException('La pregunta solicitada no existe.');
    }

    if ($this->request->isPost) {
        $attributes = $this->request->post($model->formName(), []);
        if (!is_array($attributes)) {
            $attributes = [];
        }
        unset($attributes['id_pregunta'], $attributes['categoria'], $attributes['opciones_json'], $attributes['escala_min'], $attributes['escala_max']);
        $model->load([$model->formName() => $attributes]);

        $opcionesJson = (string) $this->request->post('opciones_json', '[]');
        $opcionesArr = json_decode($opcionesJson, true);

        if (!is_array($opcionesArr) || count($opcionesArr) < 2) {
            $model->addError('opciones_json', 'Agrega al menos dos opciones.');
        } else {
            $opcionesFinales = [];
            $i = 1;
            foreach ($opcionesArr as $fila) {
                $texto = trim((string) ($fila['texto'] ?? ''));
                if ($texto === '') continue;
                $opcionesFinales[(string) $i] = $texto;
                $i++;
            }
            if (count($opcionesFinales) < 2) {
                $model->addError('opciones_json', 'Agrega al menos dos opciones válidas.');
            } else {
                $model->opciones_json = json_encode($opcionesFinales, JSON_UNESCAPED_UNICODE);
                $model->escala_min = 1;
                $model->escala_max = count($opcionesFinales);
            }
        }

        if (!$model->hasErrors() && $model->save(false)) {
            Yii::$app->session->setFlash('success', 'La pregunta se actualizó correctamente.');
            return $this->redirect(['preguntas']);
        }
    }

    return $this->render('editar-pregunta', ['model' => $model]);
}
}
