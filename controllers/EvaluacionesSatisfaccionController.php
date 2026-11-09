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

        $options = $model->getOptions();
        $opcionesTexto = implode("\n", array_values($options));

        if ($this->request->isPost) {
            $attributes = $this->request->post($model->formName(), []);
            if (!is_array($attributes)) {
                $attributes = [];
            }
            unset($attributes['id_pregunta'], $attributes['categoria'], $attributes['opciones_json']);
            $model->load([$model->formName() => $attributes]);

            $opcionesTexto = trim((string) $this->request->post('opciones_texto', ''));
            $lineas = preg_split('/\R/u', $opcionesTexto) ?: [];
            $lineas = array_values(array_filter(array_map('trim', $lineas), static fn(string $linea): bool => $linea !== ''));

            $esCalificacion = $model->categoria === 'calificacion';
            $minimo = $esCalificacion ? (int) $model->escala_min : 1;
            $maximo = $esCalificacion ? (int) $model->escala_max : count($lineas);
            $cantidadEsperada = $esCalificacion ? $maximo - $minimo + 1 : count($lineas);
            $errorOpciones = null;

            if ($lineas === []) {
                $errorOpciones = 'Agrega al menos una opción.';
            } elseif ($esCalificacion && count($lineas) !== $cantidadEsperada) {
                $errorOpciones = 'La cantidad de opciones debe coincidir con el rango mínimo y máximo.';
            } elseif (!$esCalificacion && count($lineas) > 20) {
                $errorOpciones = 'Puedes registrar hasta 20 opciones.';
            } else {
                $options = [];
                foreach ($lineas as $index => $label) {
                    $options[$esCalificacion ? $minimo + $index : $index + 1] = $label;
                }
                $model->opciones_json = json_encode($options, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            }

            $model->validate();
            if ($errorOpciones !== null) {
                $model->addError('opciones_json', $errorOpciones);
            }

            if (!$model->hasErrors()) {
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'La pregunta se actualizó correctamente.');
                    return $this->redirect(['preguntas']);
                }

                throw new \RuntimeException('No se pudo guardar la pregunta.');
            }
        }

        return $this->render('editar-pregunta', [
            'model' => $model,
            'opcionesTexto' => $opcionesTexto,
        ]);
    }
}
