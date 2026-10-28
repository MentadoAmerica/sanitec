<?php

use app\models\Roles;
use app\models\Usuarios;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\RolesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Roles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-page-actions">
            <?= Html::a(
                ' <i class="bi bi-arrow-left ms-1"></i>',
                ['usuarios/index'],
                ['class' => 'btn-secondary-admin', 'encode' => false]
            ) ?>
        </div>
<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Roles</h1>
            <p class="admin-page-subtitle">
                <?= number_format($dataProvider->getTotalCount()) ?> roles configurados
            </p>
        </div>
        
        <div class="admin-page-actions">
            <?= Html::a(
                '<i class="bi bi-plus-lg me-2"></i>Crear rol',
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
            'emptyText' => 'No hay roles registrados.',
            'columns' => [
                [
                    'attribute' => 'id_rol',
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
                    'attribute' => 'descripcion',
                    'label' => 'Descripción',
                    'value' => function ($m) {
                        return $m->descripcion ?: '—';
                    },
                    'contentOptions' => ['style' => 'max-width: 320px;'],
                    'enableSorting' => false,
                ],
                [
                    'label' => 'Usuarios',
                    'format' => 'raw',
                    'value' => function ($m) {
                        $total = Usuarios::find()->where(['id_rol' => $m->id_rol])->count();
                        if ($total === 0) {
                            return '<span class="admin-muted-count">Sin usuarios</span>';
                        }
                        return '<span class="admin-count-chip">' . number_format($total) . '</span>';
                    },
                    'headerOptions' => ['style' => 'width: 110px'],
                    'enableSorting' => false,
                    'filter' => false,
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
                                    'confirm' => '¿Confirmas eliminar este rol?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, Roles $model) {
                        return Url::to([$action, 'id_rol' => $model->id_rol]);
                    },
                ],
            ],
        ]); ?>
    </div>
</section>