<?php

namespace app\controllers;

use Yii;
use app\models\MovimientosInventario;
use app\models\MovimientosInventarioSearch;
use app\models\Materiales;
use app\models\Personal;
use app\models\Edificios;
use app\models\Sanitarios;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class MovimientosInventarioController extends Controller
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
                            return in_array($rol, [1, 4], true);
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new MovimientosInventarioSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['fecha_hora' => SORT_DESC];

        // filtro de tipo
        $tipo = $this->request->get('tipo');
        if ($tipo === 'entrada') {
            $dataProvider->query->andWhere(['like', 'tipo_movimiento', 'entrada']);
        } elseif ($tipo === 'salida') {
            $dataProvider->query->andWhere([
                'or',
                ['like', 'tipo_movimiento', 'entrega'],
                ['like', 'tipo_movimiento', 'salida'],
            ]);
        }

        // filtro de fechas
        $desde = $this->request->get('desde');
        $hasta = $this->request->get('hasta');
        if (!empty($desde)) {
            $dataProvider->query->andWhere(['>=', 'fecha_hora', $desde . ' 00:00:00']);
        }
        if (!empty($hasta)) {
            $dataProvider->query->andWhere(['<=', 'fecha_hora', $hasta . ' 23:59:59']);
        }

        // filtro por material (cuando viene del boton "historial" del catalogo)
        $idMaterial = $this->request->get('id_material');
        $material = null;
        if (!empty($idMaterial)) {
            $dataProvider->query->andWhere(['id_material' => $idMaterial]);
            $material = Materiales::findOne($idMaterial);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tipo' => $tipo,
            'desde' => $desde,
            'hasta' => $hasta,
            'material' => $material,
        ]);
    }

    public function actionView($id_movimiento)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_movimiento),
        ]);
    }

    public function actionCreate($tipo = 'entrada')
    {
        if (!in_array($tipo, ['entrada', 'salida'], true)) {
            $tipo = 'entrada';
        }

        $model = new MovimientosInventario();
        $model->tipo_movimiento = $tipo === 'entrada' ? 'Entrada por compra' : 'Entrega a intendente';
        $model->fecha_hora = date('Y-m-d H:i:s');

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->id_usuario_registra = Yii::$app->user->id;

            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                if ($model->save()) {
                    $material = Materiales::findOne($model->id_material);
                    if ($material) {
                        if ($tipo === 'entrada') {
                            $material->stock_actual = (float) $material->stock_actual + (float) $model->cantidad;
                        } else {
                            if ((float) $model->cantidad > (float) $material->stock_actual) {
                                throw new \Exception('La cantidad solicitada supera el stock disponible.');
                            }
                            $material->stock_actual = (float) $material->stock_actual - (float) $model->cantidad;
                        }
                        $material->save(false);
                    }
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', $tipo === 'entrada'
                        ? 'Entrada registrada correctamente.'
                        : 'Entrega registrada correctamente.');
                    return $this->redirect(['index']);
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                $model->addError('cantidad', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'tipo' => $tipo,
        ]);
    }

    public function actionExport()
    {
        $formato = $this->request->get('formato', 'csv');
        $tipo = $this->request->get('tipo');
        $desde = $this->request->get('desde');
        $hasta = $this->request->get('hasta');
        $idMaterial = $this->request->get('id_material');

        $query = MovimientosInventario::find()
            ->alias('mi')
            ->select([
                'mi.id_movimiento',
                'mi.tipo_movimiento',
                'mi.cantidad',
                'mi.fecha_hora',
                'm.nombre AS material',
                'm.unidad_medida',
                'e.nombre AS edificio',
                'p.tipo_personal',
                'p.identificador',
                'u.nombre AS usuario_nombre',
                'u.apellidos AS usuario_apellidos',
            ])
            ->leftJoin(['m' => Materiales::tableName()], 'm.id_material = mi.id_material')
            ->leftJoin(['e' => Edificios::tableName()], 'e.id_edificio = mi.id_edificio')
            ->leftJoin(['p' => Personal::tableName()], 'p.id_personal = mi.id_personal_receptor')
            ->leftJoin(['u' => '{{%usuarios}}'], 'u.id_usuario = mi.id_usuario_registra');

        if ($tipo === 'entrada') {
            $query->andWhere(['like', 'mi.tipo_movimiento', 'entrada']);
        } elseif ($tipo === 'salida') {
            $query->andWhere(['or',
                ['like', 'mi.tipo_movimiento', 'entrega'],
                ['like', 'mi.tipo_movimiento', 'salida'],
            ]);
        }
        if (!empty($desde)) {
            $query->andWhere(['>=', 'mi.fecha_hora', $desde . ' 00:00:00']);
        }
        if (!empty($hasta)) {
            $query->andWhere(['<=', 'mi.fecha_hora', $hasta . ' 23:59:59']);
        }
        if (!empty($idMaterial)) {
            $query->andWhere(['mi.id_material' => $idMaterial]);
        }

        $rows = $query->orderBy(['mi.fecha_hora' => SORT_DESC])->asArray()->all();

        if ($formato === 'csv') {
            $filename = 'movimientos_' . date('Ymd_His') . '.csv';
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, ['ID', 'Fecha', 'Tipo', 'Material', 'Unidad', 'Cantidad', 'Edificio', 'Receptor', 'Registrado por']);
            foreach ($rows as $r) {
                $receptor = $r['tipo_personal']
                    ? ucfirst($r['tipo_personal']) . ' · ' . $r['identificador']
                    : '';
                fputcsv($out, [
                    $r['id_movimiento'],
                    $r['fecha_hora'],
                    $r['tipo_movimiento'],
                    $r['material'],
                    $r['unidad_medida'],
                    $r['cantidad'],
                    $r['edificio'],
                    $receptor,
                    trim(($r['usuario_nombre'] ?? '') . ' ' . ($r['usuario_apellidos'] ?? '')),
                ]);
            }
            fclose($out);
            exit;
        }

        if ($formato === 'pdf') {
            $this->layout = 'main-login';
            return $this->render('export-pdf', [
                'rows' => $rows,
                'tipo' => $tipo,
                'desde' => $desde,
                'hasta' => $hasta,
            ]);
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id_movimiento)
    {
        if (($model = MovimientosInventario::findOne(['id_movimiento' => $id_movimiento])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El movimiento solicitado no existe.');
    }
}