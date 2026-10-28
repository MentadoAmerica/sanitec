<?php

use app\models\Materiales;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MaterialesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var int $totalActivos */
/** @var int $totalBajos */
/** @var float $valorInventario */

$this->title = 'Inventario de materiales';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Inventario</span>
            <h1 class="admin-page-title">Inventario de materiales</h1>
            <p class="admin-page-subtitle">
                <?= number_format($dataProvider->getTotalCount()) ?> materiales en el catálogo
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a(
                '<i class="bi bi-plus-lg me-2"></i>Crear material',
                ['create'],
                ['class' => 'btn-primary-admin', 'encode' => false]
            ) ?>
        </div>
    </header>

    <div class="admin-stat-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-label">Materiales activos</div>
            <div class="admin-stat-value"><?= number_format($totalActivos) ?></div>
        </div>
        <div class="admin-stat-card<?= $totalBajos > 0 ? ' admin-stat-warning' : '' ?>">
            <div class="admin-stat-label">Requieren reposición</div>
            <div class="admin-stat-value"><?= number_format($totalBajos) ?></div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-label">Valor del inventario</div>
            <div class="admin-stat-value">
                $<?= number_format($valorInventario, 2) ?>
            </div>
        </div>
    </div>

    <?php if ($totalBajos > 0): ?>
        <div class="admin-alert admin-alert-warning">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Atención:</strong>
                <?= number_format($totalBajos) ?> material(es) están en su stock mínimo o por debajo.
                Considera registrar una compra.
            </div>
        </div>
    <?php endif; ?>

    <div class="admin-card">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table admin-table align-middle mb-0'],
            'headerRowOptions' => ['class' => 'admin-table-head'],
            'summary' => 'Mostrando {begin} a {end} de {total} registros',
            'emptyText' => 'No hay materiales registrados.',
            'rowOptions' => function ($m) {
                if ($m->estado == 1 && $m->stock_actual <= $m->stock_minimo) {
                    return ['class' => 'admin-row-warning'];
                }
                return [];
            },
            'columns' => [
                [
                    'attribute' => 'id_material',
                    'label' => 'ID',
                    'headerOptions' => ['style' => 'width: 70px'],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'nombre',
                    'label' => 'Material',
                    'contentOptions' => ['class' => 'fw-semibold'],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'unidad_medida',
                    'label' => 'Unidad',
                    'value' => function ($m) {
                        return ucfirst($m->unidad_medida ?: '—');
                    },
                    'filter' => [
                        'pieza' => 'Pieza',
                        'paquete' => 'Paquete',
                        'rollo' => 'Rollo',
                        'litro' => 'Litro',
                        'kilogramo' => 'Kilogramo',
                        'caja' => 'Caja',
                    ],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'stock_actual',
                    'label' => 'Stock actual',
                    'format' => 'raw',
                    'value' => function ($m) {
                        $stock = rtrim(rtrim(number_format((float) $m->stock_actual, 2, '.', ','), '0'), '.');
                        return '<span class="admin-stock-value">' . Html::encode($stock) . '</span>';
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'attribute' => 'stock_minimo',
                    'label' => 'Mínimo',
                    'format' => 'raw',
                    'value' => function ($m) {
                        $min = rtrim(rtrim(number_format((float) $m->stock_minimo, 2, '.', ','), '0'), '.');
                        return '<span class="admin-muted-count">' . Html::encode($min) . '</span>';
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'label' => 'Estado del stock',
                    'format' => 'raw',
                    'value' => function ($m) {
                        if ($m->estado == 0) {
                            return '<span class="admin-badge admin-badge-inactive">Inactivo</span>';
                        }
                        if ($m->stock_actual <= 0) {
                            return '<span class="admin-badge admin-badge-danger">Sin stock</span>';
                        }
                        if ($m->stock_actual <= $m->stock_minimo) {
                            return '<span class="admin-badge admin-badge-warning">Stock bajo</span>';
                        }
                        return '<span class="admin-badge admin-badge-active">Suficiente</span>';
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'attribute' => 'costo_unitario',
                    'label' => 'Costo unitario',
                    'value' => function ($m) {
                        return $m->costo_unitario !== null
                            ? '$' . number_format((float) $m->costo_unitario, 2)
                            : '—';
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'class' => ActionColumn::class,
                    'template' => '{view} {update} {delete}',
                    'header' => 'Acciones',
                    'headerOptions' => ['style' => 'width: 220px'],
                    'buttons' => [
                        'view' => function ($url) {
                            return Html::a('Ver', $url, ['class' => 'btn-action-view']);
                        },
                        'update' => function ($url) {
                            return Html::a('Editar', $url, ['class' => 'btn-action-edit']);
                        },
                        'delete' => function ($url) {
                            return Html::a('Eliminar', $url, [
                                'class' => 'btn-action-delete',
                                'data' => [
                                    'confirm' => '¿Confirmas eliminar este material?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, Materiales $model) {
                        return Url::to([$action, 'id_material' => $model->id_material]);
                    },
                ],
            ],
        ]); ?>
    </div>
</section>