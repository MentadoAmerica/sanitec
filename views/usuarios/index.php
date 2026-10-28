<?php

use app\models\Usuarios;
use app\models\Roles;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UsuariosSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;

$roles = Roles::find()
    ->orderBy(['nombre' => SORT_ASC])
    ->select(['nombre', 'id_rol'])
    ->indexBy('id_rol')
    ->column();
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Usuarios</h1>
            <p class="admin-page-subtitle">
                <?= number_format($dataProvider->getTotalCount()) ?> usuarios registrados
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a(
                'Ir a Roles <i class="bi bi-arrow-right ms-1"></i>',
                ['roles/index'],
                ['class' => 'btn-secondary-admin', 'encode' => false]
            ) ?>
            <?= Html::a(
                '<i class="bi bi-plus-lg me-2"></i>Crear usuario',
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
            'emptyText' => 'No hay usuarios registrados.',
            'columns' => [
                [
                    'attribute' => 'id_usuario',
                    'label' => 'ID',
                    'headerOptions' => ['style' => 'width: 70px'],
                    'enableSorting' => false,
                ],
                [
                    'label' => 'Nombre completo',
                    'value' => function ($m) {
                        return $m->getNombreCompleto();
                    },
                    'contentOptions' => ['class' => 'fw-semibold'],
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'attribute' => 'correo',
                    'label' => 'Correo',
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'id_rol',
                    'label' => 'Rol',
                    'value' => function ($m) {
                        return $m->rol ? $m->rol->nombre : 'Sin rol';
                    },
                    'filter' => $roles,
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'telefono',
                    'label' => 'Teléfono',
                    'value' => function ($m) {
                        return $m->telefono ?: '—';
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
                                    'confirm' => '¿Confirmas eliminar este usuario?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, Usuarios $model) {
                        return Url::to([$action, 'id_usuario' => $model->id_usuario]);
                    },
                ],
            ],
        ]); ?>
    </div>
</section>