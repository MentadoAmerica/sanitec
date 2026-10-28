<?php

use app\models\Edificios;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\EdificiosSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Edificios';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Edificios</h1>
            <p class="admin-page-subtitle">
                <?= number_format($dataProvider->getTotalCount()) ?> edificios registrados
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a(
                '<i class="bi bi-plus-lg me-2"></i>Crear edificio',
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
            'emptyText' => 'No hay edificios registrados.',
            'columns' => [
                [
                    'attribute' => 'id_edificio',
                    'label' => 'ID',
                    'headerOptions' => ['style' => 'width: 80px'],
                ],
                [
                    'attribute' => 'nombre',
                    'label' => 'Nombre',
                    'contentOptions' => ['class' => 'fw-semibold'],
                ],
                [
                    'attribute' => 'ubicacion',
                    'label' => 'Ubicación',
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
                                    'confirm' => '¿Confirmas eliminar este edificio?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, Edificios $model) {
                        return Url::to([$action, 'id_edificio' => $model->id_edificio]);
                    },
                ],
            ],
        ]); ?>
    </div>
</section>