<?php

use app\models\AlumnosServicioSocial;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AlumnosServicioSocialSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var int $totalActivos */
/** @var int $totalInactivos */

$this->title = 'Alumnos de servicio social';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Servicio Social</span>
            <h1 class="admin-page-title">Alumnos de servicio social</h1>
            <p class="admin-page-subtitle">
                <?= number_format($dataProvider->getTotalCount()) ?> alumnos registrados
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a(
                'Ir a Inscripción de turnos <i class="bi bi-arrow-right ms-1"></i>',
                ['/alumno-horarios-ss/index'],
                ['class' => 'btn-secondary-admin', 'encode' => false]
            ) ?>
            <?= Html::a(
                '<i class="bi bi-plus-lg me-2"></i>Registrar alumno',
                ['create'],
                ['class' => 'btn-primary-admin', 'encode' => false]
            ) ?>
        </div>
    </header>

    <div class="admin-stat-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-label">Activos</div>
            <div class="admin-stat-value"><?= number_format($totalActivos) ?></div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-label">Inactivos</div>
            <div class="admin-stat-value"><?= number_format($totalInactivos) ?></div>
        </div>
    </div>

    <div class="admin-card">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table admin-table align-middle mb-0'],
            'headerRowOptions' => ['class' => 'admin-table-head'],
            'summary' => 'Mostrando {begin} a {end} de {total} registros',
            'emptyText' => 'No hay alumnos de servicio social registrados.',
            'columns' => [
                [
                    'attribute' => 'id_alumno',
                    'label' => 'ID',
                    'headerOptions' => ['style' => 'width: 70px'],
                    'enableSorting' => false,
                ],
                [
                    'label' => 'Nombre completo',
                    'value' => function ($m) {
                        return $m->usuario ? $m->usuario->getNombreCompleto() : '—';
                    },
                    'contentOptions' => ['class' => 'fw-semibold'],
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'attribute' => 'matricula',
                    'label' => 'Matrícula',
                    'format' => 'raw',
                    'value' => function ($m) {
                        if (empty($m->matricula)) {
                            return '<span class="admin-muted-count">sin matrícula</span>';
                        }
                        return '<code class="admin-code-chip">' . Html::encode($m->matricula) . '</code>';
                    },
                    'enableSorting' => false,
                ],
                [
                    'label' => 'Correo',
                    'value' => function ($m) {
                        return $m->usuario ? $m->usuario->correo : '—';
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'label' => 'Turnos',
                    'format' => 'raw',
                    'value' => function ($m) {
                        $count = (int) Yii::$app->db->createCommand(
                            'SELECT COUNT(*) FROM {{%alumno_horarios_ss}} WHERE id_alumno = :id AND estado = 1',
                            [':id' => $m->id_alumno]
                        )->queryScalar();
                        if ($count === 0) {
                            return '<span class="admin-muted-count">sin turnos</span>';
                        }
                        return '<span class="admin-count-chip">' . number_format($count) . '</span>';
                    },
                    'headerOptions' => ['style' => 'width: 100px'],
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
                                    'confirm' => '¿Confirmas eliminar este alumno del programa? Si ya tiene historial, se recomienda inactivarlo.',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, AlumnosServicioSocial $model) {
                        return Url::to([$action, 'id_alumno' => $model->id_alumno]);
                    },
                ],
            ],
        ]); ?>
    </div>
</section>