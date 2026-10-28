<?php

use app\models\TiposReporte;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\TiposReporteSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tipos de reporte';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Catálogo</span>
            <h1 class="admin-page-title">Tipos de reporte</h1>
            <p class="admin-page-subtitle">
                <?= number_format($dataProvider->getTotalCount()) ?> categorías registradas
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a(
                '<i class="bi bi-plus-lg me-2"></i>Crear tipo',
                ['create'],
                ['class' => 'btn-primary-admin', 'encode' => false]
            ) ?>
        </div>
    </header>

    <div class="admin-card">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table admin-table align-middle mb-0'],
            'headerRowOptions' => ['class' => 'admin-table-head'],
            'summary' => 'Mostrando {begin} a {end} de {total} registros',
            'emptyText' => 'No hay tipos de reporte registrados.',
            'columns' => [
                [
                    'attribute' => 'id_tipo_reporte',
                    'label' => 'ID',
                    'headerOptions' => ['style' => 'width: 70px'],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'nombre',
                    'label' => 'Nombre',
                    'contentOptions' => ['class' => 'fw-semibold'],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'categoria',
                    'label' => 'Categoría',
                    'value' => function ($m) {
                        return ucfirst($m->categoria);
                    },
                    'filter' => [
                        'limpieza' => 'Limpieza',
                        'suministros' => 'Suministros',
                        'mantenimiento' => 'Mantenimiento',
                    ],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'prioridad',
                    'label' => 'Prioridad',
                    'format' => 'raw',
                    'value' => function ($m) {
                        return Html::tag(
                            'span',
                            ucfirst($m->prioridad),
                            ['class' => 'admin-prioridad admin-prioridad-' . $m->prioridad]
                        );
                    },
                    'filter' => [
                        'baja' => 'Baja',
                        'normal' => 'Normal',
                        'alta' => 'Alta',
                        'urgente' => 'Urgente',
                    ],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'estado',
                    'label' => 'Estado',
                    'format' => 'raw',
                    'value' => function ($m) {
                        return $m->estado
                            ? '<span class="admin-badge admin-badge-active">Activo</span>'
                            : '<span class="admin-badge admin-badge-inactive">Inactivo</span>';
                    },
                    'filter' => [1 => 'Activo', 0 => 'Inactivo'],
                    'enableSorting' => false,
                ],
                [
                    'class' => ActionColumn::class,
                    'template' => '{update} {delete}',
                    'header' => 'Acciones',
                    'headerOptions' => ['style' => 'width: 180px'],
                    'buttons' => [
                        'update' => function ($url) {
                            return Html::a('Editar', $url, ['class' => 'btn-action-edit']);
                        },
                        'delete' => function ($url) {
                            return Html::a('Eliminar', $url, [
                                'class' => 'btn-action-delete',
                                'data' => [
                                    'confirm' => '¿Eliminar este tipo de reporte?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, TiposReporte $model) {
                        return Url::to([$action, 'id_tipo_reporte' => $model->id_tipo_reporte]);
                    },
                ],
            ],
        ]); ?>
    </div>
</section>