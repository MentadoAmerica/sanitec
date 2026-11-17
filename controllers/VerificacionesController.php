<?php

namespace app\controllers;

use Yii;
use app\models\Verificaciones;
use app\models\RondasProgramadas;
use app\models\PreguntasVerificacion;
use app\models\RespuestasVerificacion;
use app\models\Reportes;
use app\models\TiposReporte;
use app\models\HistorialReportes;
use app\models\AlumnosServicioSocial;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class VerificacionesController extends Controller
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
                            $rol = (int) Yii::$app->user->identity->id_rol;
                            return in_array($rol, [1, 2], true);
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $idUsuario = Yii::$app->user->id;

        $alumno = AlumnosServicioSocial::find()
            ->where(['id_usuario' => $idUsuario, 'estado' => 1])
            ->one();

        if (!$alumno) {
            return $this->render('index', [
                'alumno' => null,
                'verificaciones' => [],
            ]);
        }

        $verificaciones = Verificaciones::find()
            ->where(['id_alumno' => $alumno->id_alumno])
            ->orderBy(['fecha_hora_inicio' => SORT_DESC])
            ->limit(50)
            ->all();

        return $this->render('index', [
            'alumno' => $alumno,
            'verificaciones' => $verificaciones,
        ]);
    }

    public function actionCreate($id_ronda)
    {
        $ronda = RondasProgramadas::findOne($id_ronda);
        if (!$ronda) {
            throw new NotFoundHttpException('La ronda no existe.');
        }

        // si ya hay verificación cerrada, redirigir a verla
        $existente = Verificaciones::find()
            ->where(['id_ronda' => $id_ronda])
            ->andWhere(['IS NOT', 'fecha_hora_fin', null])
            ->one();
        if ($existente) {
            return $this->redirect(['view', 'id_verificacion' => $existente->id_verificacion]);
        }

        $agrupadas = PreguntasVerificacion::findActivasAgrupadas();

        return $this->render('create', [
            'ronda' => $ronda,
            'agrupadas' => $agrupadas,
        ]);
    }

    public function actionStore()
{
    $request = Yii::$app->request;
    if (!$request->isPost) {
        return $this->redirect(['rondas-programadas/index']);
    }

    $idRonda = (int) $request->post('id_ronda');
    $ronda = RondasProgramadas::findOne($idRonda);
    if (!$ronda) {
        throw new NotFoundHttpException('La ronda no existe.');
    }

    // verificar que no haya sido registrada mientras contestabas
    $existente = Verificaciones::find()
        ->where(['id_ronda' => $idRonda])
        ->andWhere(['IS NOT', 'fecha_hora_fin', null])
        ->exists();
    if ($existente) {
        Yii::$app->session->setFlash('error',
            'Esta ronda ya fue registrada por otro alumno mientras contestabas.');
        return $this->redirect(['/rondas-programadas/index']);
    }

    // validar PIN
    $pin = trim((string) $request->post('pin'));
    $alumno = AlumnosServicioSocial::findByPin($pin);
    if (!$alumno) {
        Yii::$app->session->setFlash('error', 'PIN no reconocido. Verifica con tu coordinador.');
        return $this->redirect(['create', 'id_ronda' => $idRonda]);
    }

    $checklist = $request->post('checklist', []);
    $observaciones = trim((string) $request->post('observaciones', ''));

    $preguntas = PreguntasVerificacion::find()
        ->where(['estado' => 1])
        ->orderBy(['orden' => SORT_ASC])
        ->all();

    // validar obligatorias
    $errores = [];
    foreach ($preguntas as $p) {
        if ((int) $p->obligatoria === 1 && !isset($checklist[$p->id_pregunta])) {
            $errores[] = 'Falta responder: ' . $p->pregunta;
        }
    }
    if (!empty($errores)) {
        Yii::$app->session->setFlash('error', implode(' · ', $errores));
        return $this->redirect(['create', 'id_ronda' => $idRonda]);
    }

    // procesar la evidencia (opcional)
    $evidenciaFile = \yii\web\UploadedFile::getInstanceByName('evidencia');
    $evidenciaPath = null;
    if ($evidenciaFile !== null) {
        $verificacionTemp = new Verificaciones();
        $verificacionTemp->evidenciaFile = $evidenciaFile;
        if (!$verificacionTemp->validate('evidenciaFile')) {
            Yii::$app->session->setFlash('error',
                'La evidencia no es válida: ' . implode(', ', $verificacionTemp->getErrors('evidenciaFile')));
            return $this->redirect(['create', 'id_ronda' => $idRonda]);
        }
        $evidenciaPath = $verificacionTemp->uploadEvidencia();
    }

    $transaction = Yii::$app->db->beginTransaction();
    try {
        $verificacion = new Verificaciones();
        $verificacion->id_ronda = $idRonda;
        $verificacion->id_alumno = $alumno->id_alumno;
        $verificacion->id_sanitario = $ronda->id_sanitario;
        $verificacion->fecha_hora_inicio = date('Y-m-d H:i:s');
        $verificacion->fecha_hora_fin = date('Y-m-d H:i:s');
        $verificacion->observaciones = $observaciones !== '' ? $observaciones : null;
        $verificacion->checklist_json = json_encode($checklist, JSON_UNESCAPED_UNICODE);
        $verificacion->evidencia_foto = $evidenciaPath;
        $verificacion->resultado_general = 'Cumple';
        $verificacion->save(false);

        $reportesCreados = 0;

        foreach ($preguntas as $p) {
            $valor = $checklist[$p->id_pregunta] ?? null;
            if ($valor === null) {
                continue;
            }

            $r = new RespuestasVerificacion();
            $r->id_verificacion = $verificacion->id_verificacion;
            $r->id_pregunta = $p->id_pregunta;
            $r->respuesta = (string) $valor;
            $r->save(false);

            foreach ($p->getTiposReporteParaRespuesta((string) $valor) as $nombreTipo) {
                $tipo = TiposReporte::findOne(['nombre' => $nombreTipo, 'estado' => 1]);
                if (!$tipo) {
                    continue;
                }

                $reporte = new Reportes();
                $reporte->id_sanitario = $ronda->id_sanitario;
                $reporte->id_tipo_reporte = $tipo->id_tipo_reporte;
                $reporte->id_verificacion = $verificacion->id_verificacion;
                $reporte->id_usuario_reporta = Yii::$app->user->id;
                $reporte->fecha_hora_reporte = date('Y-m-d H:i:s');
                $opciones = $p->getOptions();
                $reporte->descripcion = 'DETECTADO EN RONDA: '
                    . mb_strtoupper($p->pregunta, 'UTF-8')
                    . ' → '
                    . mb_strtoupper($opciones[$valor] ?? (string) $valor, 'UTF-8');
                $reporte->prioridad = $tipo->prioridad ?: 'normal';
                $reporte->estado = 'Pendiente';
                // copiar la misma evidencia a cada reporte generado
                $reporte->evidencia_foto = $evidenciaPath;
                $reporte->save(false);

                $h = new HistorialReportes();
                $h->id_reporte = $reporte->id_reporte;
                $h->id_usuario = Yii::$app->user->id;
                $h->estado_anterior = null;
                $h->estado_nuevo = 'Pendiente';
                $h->fecha_hora = date('Y-m-d H:i:s');
                $h->observaciones = 'generado automáticamente desde verificación #' . $verificacion->id_verificacion;
                $h->save(false);

                $reportesCreados++;
            }
        }

        if ($reportesCreados > 0) {
            $verificacion->resultado_general = 'Requiere atención';
            $verificacion->save(false);
        }

        $ronda->estado = 'Completada';
        $ronda->fecha_hora_inicio = $ronda->fecha_hora_inicio ?: date('Y-m-d H:i:s');
        $ronda->fecha_hora_fin = date('Y-m-d H:i:s');
        $ronda->save(false);

        $transaction->commit();

        Yii::$app->session->setFlash('success',
            "Verificación guardada. Reportes generados: {$reportesCreados}."
            . ($evidenciaPath ? ' Foto adjunta.' : ''));
        return $this->redirect(['view', 'id_verificacion' => $verificacion->id_verificacion]);
    } catch (\Throwable $e) {
        $transaction->rollBack();
        // si se subió una foto pero falló todo, borrarla
        if ($evidenciaPath) {
            $archivo = Yii::getAlias('@webroot/' . $evidenciaPath);
            if (is_file($archivo)) {
                @unlink($archivo);
            }
        }
        Yii::error($e->getMessage() . "\n" . $e->getTraceAsString(), __METHOD__);
        Yii::$app->session->setFlash('error',
            'No se pudo guardar la verificación: ' . $e->getMessage());
        return $this->redirect(['create', 'id_ronda' => $idRonda]);
    }
}

    public function actionView($id_verificacion)
    {
        $model = Verificaciones::findOne($id_verificacion);
        if (!$model) {
            throw new NotFoundHttpException('La verificación no existe.');
        }

        $respuestas = RespuestasVerificacion::find()
            ->where(['id_verificacion' => $id_verificacion])
            ->all();

        $preguntasPorId = [];
        foreach (PreguntasVerificacion::find()->all() as $p) {
            $preguntasPorId[$p->id_pregunta] = $p;
        }

        $reportes = Reportes::find()
            ->where(['id_verificacion' => $id_verificacion])
            ->all();

        return $this->render('view', [
            'model' => $model,
            'respuestas' => $respuestas,
            'preguntasPorId' => $preguntasPorId,
            'reportes' => $reportes,
        ]);
    }
}