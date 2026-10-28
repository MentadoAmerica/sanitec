<?php

use app\models\Servicios;
use app\models\Personal;
use app\models\Sanitarios;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ServiciosSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string|null $estado */

$this->title = 'Servicios';
$this->params['breadcrumbs'][] = $this->title;

$personalList = Personal::find()->orderBy(['id_personal' => SORT_ASC])->all();
$opcionesPersonal = [];
foreach ($personalList as $p) {
    $opcionesPersonal[$p->id_personal] = $p->getNombreMostrar();
}

$opcionesSanitario = Sanitarios::find()
    ->orderBy(['nombre' => SORT_ASC])
    ->select(['nombre', 'id_sanitario'])
    ->indexBy('id_sanitario')
    ->column();
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Servicios registrados</h1>
            <p class="admin-page-subtitle">
                <?= number_format($dataProvider->getTotalCount()) ?> servicios
            </p>
        </div>
    </header>

    <nav class="admin-tabs" aria-label="Estado del servicio">
        <?= Html::a(
            'Todos',
            ['index'],
            ['class' => 'btn-tab-admin' . ($estado === null ? ' active' : '')]
        ) ?>
        <?= Html::a(
            'En curso',
            ['index', 'estado' => 'abierto'],
            ['class' => 'btn-tab-admin' . ($estado === 'abierto' ? ' active' : '')]
        ) ?>
        <?= Html::a(
            'Cerrados',
            ['index', 'estado' => 'cerrado'],
            ['class' => 'btn-tab-admin' . ($estado === 'cerrado' ? ' active' : '')]
        ) ?>
    </nav>

    <div class="admin-card">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table admin-table align-middle mb-0'],
            'headerRowOptions' => ['class' => 'admin-table-head'],
            'summary' => 'Mostrando {begin} a {end} de {total} registros',
            'emptyText' => 'No hay servicios registrados.',
            'columns' => [
                [
                    'attribute' => 'id_servicio',
                    'label' => 'ID',
                    'headerOptions' => ['style' => 'width: 70px'],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'id_personal',
                    'label' => 'Trabajador',
                    'value' => function ($m) {
                        return $m->personal ? $m->personal->getNombreMostrar() : '—';
                    },
                    'filter' => $opcionesPersonal,
                    'enableSorting' => false,
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
                    'filter' => $opcionesSanitario,
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'fecha',
                    'label' => 'Fecha',
                    'format' => ['date', 'php:d/m/Y'],
                    'enableSorting' => false,
                    'filter' => Html::input(
                        'date',
                        'ServiciosSearch[fecha]',
                        $searchModel->fecha,
                        ['class' => 'form-control', 'style' => 'min-width: 130px']
                    ),
                ],
                [
                    'attribute' => 'hora_checkin',
                    'label' => 'Entrada',
                    'value' => function ($m) {
                        return Yii::$app->formatter->asTime($m->hora_checkin, 'php:H:i');
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'attribute' => 'hora_checkout',
                    'label' => 'Salida',
                    'format' => 'raw',
                    'value' => function ($m) {
                        if ($m->hora_checkout === null) {
                            return '<span class="admin-muted-count">—</span>';
                        }
                        return Yii::$app->formatter->asTime($m->hora_checkout, 'php:H:i');
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'label' => 'Duración',
                    'value' => function ($m) {
                        return $m->getDuracionFormateada();
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'attribute' => 'estado',
                    'label' => 'Estado',
                    'format' => 'raw',
                    'value' => function ($m) {
                        return $m->getEstaAbierto()
                            ? '<span class="admin-badge admin-badge-warning">En curso</span>'
                            : '<span class="admin-badge admin-badge-active">Cerrado</span>';
                    },
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'class' => ActionColumn::class,
                    'template' => '{view} {update}',
                    'header' => 'Acciones',
                    'headerOptions' => ['style' => 'width: 180px'],
                    'buttons' => [
                        'view' => function ($url) {
                            return Html::a('Ver', $url, ['class' => 'btn-action-view']);
                        },
                        'update' => function ($url) {
                            return Html::a('Editar', $url, ['class' => 'btn-action-edit']);
                        },
                    ],
                    'urlCreator' => function ($action, Servicios $model) {
                        return Url::to([$action, 'id_servicio' => $model->id_servicio]);
                    },
                ],
            ],
        ]); ?>
    </div>
</section>