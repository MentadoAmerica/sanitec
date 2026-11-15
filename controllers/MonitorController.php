<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\db\Expression;

class MonitorController extends Controller
{
    public $layout = 'main-monitor';

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
                            return in_array($rol, [1, 3], true);
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $reportes = (new Query())
            ->select([
                'r.id_reporte',
                'r.fecha_hora_reporte',
                'r.descripcion',
                'r.estado',
                'r.prioridad AS prioridad_reporte',
                'tr.prioridad AS prioridad_tipo',
                'tr.nombre AS tipo_nombre',
                'tr.categoria',
                's.nombre AS sanitario_nombre',
                's.tipo AS sanitario_tipo',
                's.ubicacion AS sanitario_ubicacion',
                'e.nombre AS edificio_nombre',
            ])
            ->from(['r' => '{{%reportes}}'])
            ->innerJoin(['tr' => '{{%tipos_reporte}}'], 'tr.id_tipo_reporte = r.id_tipo_reporte')
            ->innerJoin(['s' => '{{%sanitarios}}'], 's.id_sanitario = r.id_sanitario')
            ->innerJoin(['e' => '{{%edificios}}'], 'e.id_edificio = s.id_edificio')
            ->where(new Expression(
                "LOWER(r.estado) NOT IN ('atendido', 'cerrado', 'cerrada')"
            ))
            ->orderBy(new Expression(
                "CASE LOWER(COALESCE(NULLIF(r.prioridad, ''), tr.prioridad)) " .
                "WHEN 'urgente' THEN 0 " .
                "WHEN 'alta' THEN 1 " .
                "WHEN 'normal' THEN 2 " .
                "WHEN 'baja' THEN 3 " .
                "ELSE 4 END ASC, r.fecha_hora_reporte ASC"
            ))
            ->all();

        return $this->render('index', [
            'reportes' => $reportes,
            'totalActivos' => count($reportes),
        ]);
    }
}