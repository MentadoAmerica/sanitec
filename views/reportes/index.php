<?php

use app\models\Reportes;
use app\models\TiposReporte;
use app\models\Sanitarios;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ReportesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string|null $estado */
/** @var array $conteos */

$this->title = 'Reportes e incidencias';
$this->params['breadcrumbs'][] = $this->title;

$tipos = TiposReporte::find()
    ->where(['estado' => 1])
    ->orderBy(['nombre' => SORT_ASC])
    ->select(['nombre', 'id_tipo_reporte'])
    ->indexBy('id_tipo_reporte')
    ->column();

$sanitarios = Sanitarios::find()
    ->orderBy(['nombre' => SORT_ASC])
    ->select(['nombre', 'id_sanitario'])
    ->indexBy('id_sanitario')
    ->column();

$esAdmin = (int) Yii::$app->user->identity->id_rol === 1;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Operación</span>
            <h1 class="admin-page-title">Reportes e incidencias</h1>
            <p class="admin-page-subtitle">
                <?= number_format($conteos['todos']) ?> reportes registrados
            </p>
        </div>
        <?php if ($esAdmin): ?>
            <div class="admin-page-actions">
                <?= Html::a(
                    '<i class="bi bi-plus-lg me-2"></i>Crear reporte',
                    ['create'],
                    ['class' => 'btn-primary-admin', 'encode' => false]
                ) ?>
            </div>
        <?php endif; ?>
    </header>

    <div class="admin-stat-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-label">Pendientes</div>
            <div class="admin-stat-value"><?= number_format($conteos['pendiente']) ?></div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-label">En atención</div>
            <div class="admin-stat-value"><?= number_format($conteos['en_atencion']) ?></div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-label">Atendidos</div>
            <div class="admin-stat-value"><?= number_format($conteos['atendido']) ?></div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-label">Cerrados</div>
            <div class="admin-stat-value"><?= number_format($conteos['cerrado']) ?></div>
        </div>
    </div>

    <nav class="admin-tabs" aria-label="Filtrar por estado">
        <?= Html::a('Todos', ['index'], ['class' => 'btn-tab-admin' . ($estado === null ? ' active' : '')]) ?>
        <?= Html::a('Pendientes', ['index', 'estado' => 'Pendiente'], ['class' => 'btn-tab-admin' . ($estado === 'Pendiente' ? ' active' : '')]) ?>
        <?= Html::a('En atención', ['index', 'estado' => 'En atención'], ['class' => 'btn-tab-admin' . ($estado === 'En atención' ? ' active' : '')]) ?>
        <?= Html::a('Atendidos', ['index', 'estado' => 'Atendido'], ['class' => 'btn-tab-admin' . ($estado === 'Atendido' ? ' active' : '')]) ?>
        <?= Html::a('Cerrados', ['index', 'estado' => 'Cerrado'], ['class' => 'btn-tab-admin' . ($estado === 'Cerrado' ? ' active' : '')]) ?>
    </nav>

    <div class="admin-card">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table admin-table align-middle mb-0'],
            'headerRowOptions' => ['class' => 'admin-table-head'],
            'summary' => 'Mostrando {begin} a {end} de {total} registros',
            'emptyText' => 'No hay reportes registrados.',
            'columns' => [
                [
                    'attribute' => 'id_reporte',
                    'label' => 'ID',
                    'headerOptions' => ['style' => 'width: 70px'],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'fecha_hora_reporte',
                    'label' => 'Fecha',
                    'format' => ['datetime', 'php:d/m/Y H:i'],
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'attribute' => 'id_sanitario',
                    'label' => 'Sanitario',
                    'format' => 'raw',
                    'value' => function ($m) {
                        if (!$m->sanitario) {
                            return '—';
                        }
                        $edificio = $m->sanitario->edificio ? $m->sanitario->edificio->nombre : '';
                        return Html::encode($m->sanitario->nombre)
                            . ($edificio ? '<br><small class="text-muted">' . Html::encode($edificio) . '</small>' : '');
                    },
                    'filter' => $sanitarios,
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'id_tipo_reporte',
                    'label' => 'Tipo',
                    'value' => function ($m) {
                        return $m->tipoReporte ? $m->tipoReporte->nombre : '—';
                    },
                    'filter' => $tipos,
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'prioridad',
                    'label' => 'Prioridad',
                    'format' => 'raw',
                    'value' => function ($m) {
                        return Html::tag(
                            'span',
                            ucfirst($m->prioridad ?: 'normal'),
                            ['class' => 'admin-prioridad admin-prioridad-' . ($m->prioridad ?: 'normal')]
                        );
                    },
                    'filter' => ['baja' => 'Baja', 'normal' => 'Normal', 'alta' => 'Alta', 'urgente' => 'Urgente'],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'estado',
                    'label' => 'Estado',
                    'format' => 'raw',
                    'value' => function ($m) {
                        return Html::tag('span', $m->estado, ['class' => 'admin-badge ' . $m->getClaseEstado()]);
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'label' => 'Reportado por',
                    'value' => function ($m) {
                        return $m->usuarioReporta ? $m->usuarioReporta->getNombreCompleto() : '—';
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
                    'urlCreator' => function ($action, Reportes $model) {
                        return Url::to([$action, 'id_reporte' => $model->id_reporte]);
                    },
                ],
            ],
        ]); ?>
    </div>
</section>