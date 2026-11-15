<?php

namespace app\controllers;

use Yii;
use app\models\RondasProgramadas;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\db\Query;

class RondasProgramadasController extends Controller
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
        $hoy = date('Y-m-d');

        $filas = (new Query())
            ->select([
                'rp.id_ronda',
                'rp.estado AS estado_ronda',
                'rp.hora_programada',
                'rp.fecha_programada',
                'hss.nombre_turno',
                'hss.hora_inicio',
                'hss.hora_fin',
                's.id_sanitario',
                's.nombre AS sanitario_nombre',
                'e.id_edificio',
                'e.nombre AS edificio_nombre',
                'v.id_verificacion',
                'v.fecha_hora_fin AS verificacion_fin',
            ])
            ->from(['rp' => '{{%rondas_programadas}}'])
            ->innerJoin(['hss' => '{{%horarios_servicio_social}}'], 'hss.id_horario_ss = rp.id_horario_ss')
            ->innerJoin(['s' => '{{%sanitarios}}'], 's.id_sanitario = rp.id_sanitario')
            ->innerJoin(['e' => '{{%edificios}}'], 'e.id_edificio = s.id_edificio')
            ->leftJoin(['v' => '{{%verificaciones}}'],
                'v.id_ronda = rp.id_ronda AND v.fecha_hora_fin IS NOT NULL')
            ->where(['rp.fecha_programada' => $hoy])
            ->orderBy([
                'hss.hora_inicio' => SORT_ASC,
                'e.nombre' => SORT_ASC,
                'rp.hora_programada' => SORT_ASC,
            ])
            ->all();

        // agrupar por turno + edificio
        $grupos = [];
        foreach ($filas as $f) {
            $clave = $f['nombre_turno'] . '|' . $f['edificio_nombre'] . '|' . $f['hora_inicio'];
            if (!isset($grupos[$clave])) {
                $grupos[$clave] = [
                    'turno' => $f['nombre_turno'],
                    'horario' => substr($f['hora_inicio'], 0, 5) . ' - ' . substr($f['hora_fin'], 0, 5),
                    'edificio' => $f['edificio_nombre'],
                    'id_edificio' => $f['id_edificio'],
                    'sanitarios' => [],
                ];
            }
            $grupos[$clave]['sanitarios'][] = $f;
        }

        return $this->render('index', [
            'grupos' => array_values($grupos),
            'fecha' => $hoy,
        ]);
    }

    public function actionView($id_ronda)
    {
        $model = RondasProgramadas::findOne($id_ronda);
        if (!$model) {
            throw new NotFoundHttpException('La ronda no existe.');
        }
        return $this->render('view', ['model' => $model]);
    }
}