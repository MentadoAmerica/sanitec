<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Materiales $model */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Inventario', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$formatear = function ($v) {
    return rtrim(rtrim(number_format((float) $v, 2, '.', ','), '0'), '.');
};

$stock = (float) $model->stock_actual;
$min = (float) $model->stock_minimo;
$porcentaje = $min > 0 ? min(100, ($stock / ($min * 2)) * 100) : ($stock > 0 ? 100 : 0);
$claseBarra = 'admin-stock-bar-ok';
if ($stock <= 0) {
    $claseBarra = 'admin-stock-bar-danger';
} elseif ($stock <= $min) {
    $claseBarra = 'admin-stock-bar-warning';
}
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Material #<?= Html::encode($model->id_material) ?></span>
            <h1 class="admin-page-title"><?= Html::encode($model->nombre) ?></h1>
            <p class="admin-page-subtitle"><?= Html::encode($model->descripcion ?: 'Sin descripción') ?></p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a('Editar', ['update', 'id_material' => $model->id_material], ['class' => 'btn-primary-admin']) ?>
            <?= Html::a('Volver al inventario', ['index'], ['class' => 'btn-cancel-admin']) ?>
        </div>
    </header>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="admin-card">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table admin-table admin-detail-table mb-0'],
                    'attributes' => [
                        [
                            'label' => 'Unidad de medida',
                            'value' => ucfirst($model->unidad_medida ?: '—'),
                        ],
                        [
                            'attribute' => 'stock_actual',
                            'label' => 'Stock actual',
                            'value' => $formatear($model->stock_actual) . ' ' . ($model->unidad_medida ?: ''),
                        ],
                        [
                            'attribute' => 'stock_minimo',
                            'label' => 'Stock mínimo',
                            'value' => $formatear($model->stock_minimo) . ' ' . ($model->unidad_medida ?: ''),
                        ],
                        [
                            'attribute' => 'costo_unitario',
                            'label' => 'Costo unitario',
                            'value' => $model->costo_unitario !== null
                                ? '$' . number_format((float) $model->costo_unitario, 2)
                                : '—',
                        ],
                        [
                            'label' => 'Valor en inventario',
                            'value' => $model->costo_unitario !== null
                                ? '$' . number_format((float) $model->stock_actual * (float) $model->costo_unitario, 2)
                                : '—',
                        ],
                        [
                            'attribute' => 'estado',
                            'label' => 'Estado',
                            'format' => 'raw',
                            'value' => $model->estado
                                ? '<span class="admin-badge admin-badge-active">Activo</span>'
                                : '<span class="admin-badge admin-badge-inactive">Inactivo</span>',
                        ],
                    ],
                ]); ?>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="admin-card">
                <h3 class="admin-form-section-title">Nivel de stock</h3>
                <p class="admin-form-section-hint">
                    El stock actual se compara con el doble del mínimo.
                </p>

                <div class="admin-stock-bar-wrapper">
                    <div class="admin-stock-bar <?= $claseBarra ?>" style="width: <?= round($porcentaje) ?>%"></div>
                </div>

                <div class="admin-stock-legend mt-3">
                    <div class="admin-stock-legend-row">
                        <span class="admin-stock-legend-label">Actual</span>
                        <span class="admin-stock-legend-value"><?= $formatear($stock) ?></span>
                    </div>
                    <div class="admin-stock-legend-row">
                        <span class="admin-stock-legend-label">Mínimo</span>
                        <span class="admin-stock-legend-value"><?= $formatear($min) ?></span>
                    </div>
                </div>

                <?php if ($stock <= $min && $model->estado): ?>
                    <div class="admin-alert admin-alert-warning mt-3 mb-0">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>Este material requiere reposición.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>