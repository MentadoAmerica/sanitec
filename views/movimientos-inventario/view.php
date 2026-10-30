<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\MovimientosInventario $model */

$this->title = 'Movimiento #' . $model->id_movimiento;
$this->params['breadcrumbs'][] = ['label' => 'Historial', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$formatear = function ($v) {
    return rtrim(rtrim(number_format((float) $v, 2, '.', ','), '0'), '.');
};
$esEntrada = stripos($model->tipo_movimiento, 'entrada') !== false;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Movimiento #<?= Html::encode($model->id_movimiento) ?></span>
            <h1 class="admin-page-title"><?= Html::encode($model->tipo_movimiento) ?></h1>
            <p class="admin-page-subtitle">
                <?= Html::encode(Yii::$app->formatter->asDatetime($model->fecha_hora, 'php:d/m/Y H:i')) ?>
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a('Volver al historial', ['index'], ['class' => 'btn-cancel-admin']) ?>
        </div>
    </header>

    <div class="admin-card">
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table admin-table admin-detail-table mb-0'],
            'attributes' => [
                ['attribute' => 'id_movimiento', 'label' => 'ID'],
                [
                    'label' => 'Tipo',
                    'format' => 'raw',
                    'value' => '<span class="admin-badge ' . ($esEntrada ? 'admin-badge-active' : 'admin-badge-info') . '">'
                        . Html::encode($model->tipo_movimiento) . '</span>',
                ],
                [
                    'label' => 'Material',
                    'value' => $model->material ? $model->material->nombre : '—',
                ],
                [
                    'label' => 'Cantidad',
                    'value' => $formatear($model->cantidad)
                        . ' ' . ($model->material ? $model->material->unidad_medida : ''),
                ],
                [
                    'label' => 'Costo unitario',
                    'value' => $model->costo_unitario !== null
                        ? '$' . number_format((float) $model->costo_unitario, 2) : '—',
                ],
                [
                    'label' => 'Costo total',
                    'value' => $model->costo_total !== null
                        ? '$' . number_format((float) $model->costo_total, 2) : '—',
                ],
                [
                    'label' => 'Receptor',
                    'value' => $model->personalReceptor
                        ? ($model->personalReceptor->usuario
                            ? $model->personalReceptor->usuario->getNombreCompleto()
                            : ucfirst($model->personalReceptor->tipo_personal) . ' · ' . $model->personalReceptor->identificador)
                        : '—',
                ],
                ['attribute' => 'id_edificio', 'label' => 'Edificio',
                    'value' => $model->edificio ? $model->edificio->nombre : '—'],
                ['attribute' => 'id_sanitario', 'label' => 'Sanitario',
                    'value' => $model->sanitario ? $model->sanitario->nombre : '—'],
                ['attribute' => 'proveedor', 'label' => 'Proveedor'],
                ['attribute' => 'folio_documento', 'label' => 'Folio'],
                [
                    'label' => 'Registrado por',
                    'value' => $model->usuarioRegistra
                        ? $model->usuarioRegistra->getNombreCompleto() : '—',
                ],
                ['attribute' => 'observaciones', 'label' => 'Observaciones',
                    'value' => $model->observaciones ?: '—'],
            ],
        ]); ?>
    </div>
</section>