<?php

use app\models\MovimientosInventario;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MovimientosInventarioSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string|null $tipo */
/** @var string|null $desde */
/** @var string|null $hasta */
/** @var app\models\Materiales|null $material */

$this->title = $material ? 'Historial · ' . $material->nombre : 'Historial de movimientos';
$this->params['breadcrumbs'][] = ['label' => 'Inventario', 'url' => ['/materiales/index']];
$this->params['breadcrumbs'][] = $this->title;

$formatear = function ($v) {
    return rtrim(rtrim(number_format((float) $v, 2, '.', ','), '0'), '.');
};

$urlBase = ['index'];
if ($material) $urlBase['id_material'] = $material->id_material;
$urlBaseSinTipo = $urlBase;
$urlEntrada = array_merge($urlBase, ['tipo' => 'entrada']);
$urlSalida = array_merge($urlBase, ['tipo' => 'salida']);
$urlTodos = $urlBase;
if (!empty($desde)) { $urlTodos['desde'] = $desde; }
if (!empty($hasta)) { $urlTodos['hasta'] = $hasta; }

$exportParams = $urlBase;
if ($tipo) $exportParams['tipo'] = $tipo;
if ($desde) $exportParams['desde'] = $desde;
if ($hasta) $exportParams['hasta'] = $hasta;
$csvUrl = array_merge(['export', 'formato' => 'csv'], $exportParams);
$pdfUrl = array_merge(['export', 'formato' => 'pdf'], $exportParams);
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Inventario</span>
            <h1 class="admin-page-title"><?= Html::encode($this->title) ?></h1>
            <p class="admin-page-subtitle">
                <?= number_format($dataProvider->getTotalCount()) ?> movimientos registrados
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a('Volver al catálogo', ['/materiales/index'], ['class' => 'btn-secondary-admin']) ?>
        </div>
    </header>

    <nav class="admin-tabs" aria-label="Tipo de movimiento">
        <?= Html::a('Todos', $urlTodos, ['class' => 'btn-tab-admin' . ($tipo === null ? ' active' : '')]) ?>
        <?= Html::a('Solo entradas', $urlEntrada, ['class' => 'btn-tab-admin' . ($tipo === 'entrada' ? ' active' : '')]) ?>
        <?= Html::a('Solo salidas', $urlSalida, ['class' => 'btn-tab-admin' . ($tipo === 'salida' ? ' active' : '')]) ?>
    </nav>

    <form method="get" action="<?= Url::to(['index']) ?>" class="admin-filter-bar">
        <?php if ($material): ?>
            <input type="hidden" name="id_material" value="<?= Html::encode($material->id_material) ?>">
        <?php endif; ?>
        <?php if ($tipo): ?>
            <input type="hidden" name="tipo" value="<?= Html::encode($tipo) ?>">
        <?php endif; ?>
        <div class="admin-filter-field">
            <label class="form-label" for="desde">Desde</label>
            <input type="date" id="desde" name="desde" value="<?= Html::encode($desde) ?>" class="form-control">
        </div>
        <div class="admin-filter-field">
            <label class="form-label" for="hasta">Hasta</label>
            <input type="date" id="hasta" name="hasta" value="<?= Html::encode($hasta) ?>" class="form-control">
        </div>
        <div class="admin-filter-actions">
            <?= Html::submitButton('Filtrar', ['class' => 'btn-primary-admin']) ?>
            <?= Html::a('Limpiar', ['index'], ['class' => 'btn-cancel-admin']) ?>
        </div>
        <div class="admin-filter-actions ms-auto">
            <?= Html::a('<i class="bi bi-filetype-csv me-1"></i>Descargar CSV', $csvUrl, ['class' => 'btn-secondary-admin']) ?>
            <?= Html::a('<i class="bi bi-filetype-pdf me-1"></i>Descargar PDF', $pdfUrl, ['class' => 'btn-secondary-admin', 'target' => '_blank']) ?>
        </div>
    </form>

    <div class="admin-card">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table admin-table align-middle mb-0'],
            'headerRowOptions' => ['class' => 'admin-table-head'],
            'summary' => 'Mostrando {begin} a {end} de {total} registros',
            'emptyText' => 'No hay movimientos registrados en este periodo.',
            'columns' => [
                [
                    'attribute' => 'id_movimiento',
                    'label' => 'ID',
                    'headerOptions' => ['style' => 'width: 70px'],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'fecha_hora',
                    'label' => 'Fecha',
                    'format' => ['datetime', 'php:d/m/Y H:i'],
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'label' => 'Tipo',
                    'format' => 'raw',
                    'value' => function ($m) {
                        $esEntrada = stripos($m->tipo_movimiento, 'entrada') !== false;
                        $clase = $esEntrada ? 'admin-badge-active' : 'admin-badge-info';
                        return '<span class="admin-badge ' . $clase . '">' . Html::encode($m->tipo_movimiento) . '</span>';
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'label' => 'Material',
                    'value' => function ($m) {
                        return $m->material ? $m->material->nombre : '—';
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'attribute' => 'cantidad',
                    'label' => 'Cantidad',
                    'value' => function ($m) use ($formatear) {
                        $unidad = $m->material ? $m->material->unidad_medida : '';
                        return $formatear($m->cantidad) . ' ' . $unidad;
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'label' => 'Destino',
                    'value' => function ($m) {
                        if ($m->edificio) return $m->edificio->nombre;
                        return '—';
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'class' => ActionColumn::class,
                    'template' => '{view}',
                    'header' => 'Acciones',
                    'headerOptions' => ['style' => 'width: 100px'],
                    'buttons' => [
                        'view' => function ($url) {
                            return Html::a('Ver', $url, ['class' => 'btn-action-view']);
                        },
                    ],
                    'urlCreator' => function ($action, MovimientosInventario $model) {
                        return Url::to([$action, 'id_movimiento' => $model->id_movimiento]);
                    },
                ],
            ],
        ]); ?>
    </div>
</section>