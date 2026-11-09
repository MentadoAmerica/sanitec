<?php

namespace app\controllers;

use Yii;
use app\models\Edificios;
use app\models\EvaluacionesSatisfaccion;
use app\models\PreguntasSatisfaccion;
use app\models\RespuestasSatisfaccion;
use app\models\Sanitarios;
use app\models\User;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class EncuestasController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Yii::$app->user->identity instanceof User
                                && (int) Yii::$app->user->identity->id_rol === 1;
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['qr', 'guardar'],
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['GET'],
                    'qr' => ['GET'],
                    'guardar' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $query = EvaluacionesSatisfaccion::find()
            ->select(['evaluaciones_satisfaccion.*', 's.nombre AS sanitario_nombre', 'e.nombre AS edificio_nombre'])
            ->leftJoin('{{%sanitarios}} s', 's.id_sanitario = evaluaciones_satisfaccion.id_sanitario')
            ->leftJoin('{{%edificios}} e', 'e.id_edificio = s.id_edificio')
            ->orderBy(['fecha_hora' => SORT_DESC]);

        $provider = new ArrayDataProvider([
            'allModels' => $query->asArray()->all(),
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('index', ['dataProvider' => $provider]);
    }

    public function actionQr(?string $token = null): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            throw new \yii\web\ForbiddenHttpException('La encuesta es pública para usuarios externos; el administrador debe consultar los resultados desde la sección de administración.');
        }

        $token = trim((string) $token);
        $sanitario = $this->findSanitarioByToken($token);

        if ($sanitario === null) {
            throw new NotFoundHttpException('El código QR no es válido o no está activo.');
        }

        $preguntas = PreguntasSatisfaccion::find()->where(['estado' => 1])->orderBy(['id_pregunta' => SORT_ASC])->all();
        $edificio = $sanitario->edificio;

        return $this->render('encuesta', [
            'sanitario' => $sanitario,
            'edificio' => $edificio,
            'preguntas' => $preguntas,
            'token' => $token,
        ]);
    }

    public function actionGuardar(): Response
    {
        if (!Yii::$app->user->isGuest) {
            return $this->asJson(['success' => false, 'message' => 'La encuesta es pública y no está disponible para usuarios autenticados.']);
        }

        $request = Yii::$app->request;
        $token = trim((string) $request->post('token', ''));
        $sanitario = $this->findSanitarioByToken($token);

        if ($sanitario === null) {
            return $this->asJson(['success' => false, 'message' => 'Código QR inválido o inactivo.']);
        }

        if (Yii::$app->request->isPost === false) {
            return $this->asJson(['success' => false, 'message' => 'Solicitud no válida.']);
        }

        $session = Yii::$app->session;
        $sessionKey = 'survey_submitted_' . md5($token . ':' . date('YmdHi'));
        if ($session->get($sessionKey, false)) {
            return $this->asJson(['success' => false, 'message' => 'La encuesta ya fue enviada recientemente.']);
        }

        $post = $request->post();
        $preguntas = PreguntasSatisfaccion::find()->where(['estado' => 1])->orderBy(['id_pregunta' => SORT_ASC])->all();
        $calificaciones = [];
        $respuestasUnicas = [];
        $seleccionesMultiples = [];

        foreach ($preguntas as $pregunta) {
            $idPregunta = (int) $pregunta->id_pregunta;
            $opciones = $pregunta->getOptions();

            if ($pregunta->categoria === 'mantenimiento') {
                $seleccionados = $post['pregunta_' . $idPregunta] ?? [];
                if (!is_array($seleccionados)) {
                    return $this->asJson(['success' => false, 'message' => 'Las opciones de mantenimiento no son válidas.']);
                }

                $seleccionados = array_values(array_unique(array_map('intval', $seleccionados)));
                foreach ($seleccionados as $valor) {
                    if (!array_key_exists($valor, $opciones)) {
                        return $this->asJson(['success' => false, 'message' => 'Las opciones de mantenimiento no son válidas.']);
                    }
                }
                $seleccionesMultiples[$idPregunta] = $seleccionados;
                continue;
            }

            $valor = $post['pregunta_' . $idPregunta] ?? null;
            $valorValidado = filter_var($valor, FILTER_VALIDATE_INT);
            if ($valor === null || $valorValidado === false) {
                return $this->asJson(['success' => false, 'message' => 'Falta responder una o varias preguntas.']);
            }

            $valorInt = (int) $valorValidado;
            if (!array_key_exists($valorInt, $opciones)) {
                if ($pregunta->categoria === 'frecuencia') {
                    return $this->asJson(['success' => false, 'message' => 'La frecuencia de uso seleccionada no es válida.']);
                }
                return $this->asJson(['success' => false, 'message' => 'La respuesta seleccionada no es válida.']);
            }

            $respuestasUnicas[$idPregunta] = $valorInt;
            if ($pregunta->categoria === 'calificacion') {
                if ($valorInt < (int) $pregunta->escala_min || $valorInt > (int) $pregunta->escala_max) {
                    return $this->asJson(['success' => false, 'message' => 'La calificación está fuera del rango permitido.']);
                }
                $calificaciones[$idPregunta] = $valorInt;
            }
        }

        $comentario = trim((string) ($post['comentario'] ?? ''));
        $fechaHora = new \DateTimeImmutable('now', new \DateTimeZone('America/Matamoros'));
        $turno = EvaluacionesSatisfaccion::resolveTurno($fechaHora->format('Y-m-d H:i:s'));

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $evaluacion = new EvaluacionesSatisfaccion();
            $evaluacion->id_sanitario = (int) $sanitario->id_sanitario;
            $evaluacion->fecha_hora = $fechaHora->format('Y-m-d H:i:s');
            $evaluacion->turno = $turno;
            $evaluacion->comentario = $comentario !== '' ? $comentario : null;
            $evaluacion->calificacion_general = $this->promedioCalificaciones($calificaciones);

            if (!$evaluacion->save()) {
                throw new \RuntimeException('No se pudo guardar la evaluación.');
            }

            foreach ($respuestasUnicas as $idPregunta => $valor) {
                $respuesta = new RespuestasSatisfaccion();
                $respuesta->id_evaluacion = (int) $evaluacion->id_evaluacion;
                $respuesta->id_pregunta = (int) $idPregunta;
                $respuesta->respuesta = (string) $valor;
                if (!$respuesta->save()) {
                    throw new \RuntimeException('No se pudo guardar una respuesta.');
                }
            }

            foreach ($seleccionesMultiples as $idPregunta => $seleccionados) {
                foreach ($seleccionados as $valor) {
                    $respuesta = new RespuestasSatisfaccion();
                    $respuesta->id_evaluacion = (int) $evaluacion->id_evaluacion;
                    $respuesta->id_pregunta = (int) $idPregunta;
                    $respuesta->respuesta = (string) $valor;
                    if (!$respuesta->save()) {
                        throw new \RuntimeException('No se pudo guardar la selección de mantenimiento.');
                    }
                }
            }

            $session->set($sessionKey, true);
            $transaction->commit();
            return $this->asJson(['success' => true, 'message' => 'Encuesta enviada correctamente.']);
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage(), __METHOD__);
            return $this->asJson(['success' => false, 'message' => 'Ocurrió un error al guardar la encuesta.']);
        }
    }

    private function promedioCalificaciones(array $calificaciones): int
    {
        if ($calificaciones === []) {
            return 0;
        }

        $total = 0;
        foreach ($calificaciones as $valor) {
            $total += (int) $valor;
        }

        return (int) round($total / count($calificaciones));
    }

    private function findSanitarioByToken(?string $token): ?Sanitarios
    {
        if ($token === null || trim($token) === '') {
            return null;
        }

        return Sanitarios::find()
            ->where(['codigo_qr' => trim($token), 'estado' => 1])
            ->one();
    }
}
