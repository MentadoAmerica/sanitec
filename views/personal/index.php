<?php

use app\models\Personal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PersonalSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Personal de limpieza';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Personal de limpieza</h1>
            <p class="admin-page-subtitle">
                <?= number_format($dataProvider->getTotalCount()) ?> trabajadores registrados
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a(
                '<i class="bi bi-plus-lg me-2"></i>Crear registro',
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
            'emptyText' => 'No hay personal registrado.',
            'columns' => [
                [
                    'attribute' => 'id_personal',
                    'label' => 'ID',
                    'headerOptions' => ['style' => 'width: 70px'],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'tipo_personal',
                    'label' => 'Tipo',
                    'value' => function ($m) {
                        return ucfirst($m->tipo_personal);
                    },
                    'enableSorting' => false,
                ],
                [
                    'label' => 'Cuenta vinculada',
                    'format' => 'raw',
                    'value' => function ($m) {
                        if ($m->usuario !== null) {
                            return '<span class="fw-semibold">' . Html::encode($m->usuario->getNombreCompleto()) . '</span>';
                        }
                        return '<span class="admin-muted-count">Sin cuenta web</span>';
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'attribute' => 'identificador',
                    'label' => 'Identificador',
                    'format' => 'raw',
                    'value' => function ($m) {
                        return '<code class="admin-code-chip">' . Html::encode($m->identificador) . '</code>';
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
                                    'confirm' => '¿Confirmas eliminar este registro de personal?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, Personal $model) {
                        return Url::to([$action, 'id_personal' => $model->id_personal]);
                    },
                ],
            ],
        ]); ?>
    </div>
</section>