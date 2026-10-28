<?php

use app\models\Sanitarios;
use app\models\Edificios;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\SanitariosSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Sanitarios';
$this->params['breadcrumbs'][] = $this->title;

$edificios = Edificios::find()
    ->orderBy(['nombre' => SORT_ASC])
    ->select(['nombre', 'id_edificio'])
    ->indexBy('id_edificio')
    ->column();
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Sanitarios</h1>
            <p class="admin-page-subtitle">
                <?= number_format($dataProvider->getTotalCount()) ?> sanitarios registrados
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a(
                '<i class="bi bi-plus-lg me-2"></i>Crear sanitario',
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
            'emptyText' => 'No hay sanitarios registrados.',
            'columns' => [
                [
                    'attribute' => 'id_sanitario',
                    'label' => 'ID',
                    'headerOptions' => ['style' => 'width: 70px'],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'id_edificio',
                    'label' => 'Edificio',
                    'value' => function ($m) {
                        return $m->edificio ? $m->edificio->nombre : 'Sin asignar';
                    },
                    'filter' => $edificios,
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'nombre',
                    'label' => 'Nombre',
                    'contentOptions' => ['class' => 'fw-semibold'],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'tipo',
                    'label' => 'Tipo',
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'ubicacion',
                    'label' => 'Ubicación',
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'codigo_qr',
                    'label' => 'Código QR',
                    'format' => 'raw',
                    'value' => function ($m) {
                        $texto = $m->codigo_qr ?: 'sin código';
                        return '<code class="admin-code-chip">' . Html::encode($texto) . '</code>';
                    },
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
                                    'confirm' => '¿Confirmas eliminar este sanitario?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, Sanitarios $model) {
                        return Url::to([$action, 'id_sanitario' => $model->id_sanitario]);
                    },
                ],
            ],
        ]); ?>
    </div>
</section>