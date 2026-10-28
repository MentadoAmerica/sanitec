<?php

use app\models\AlumnoHorariosSs;
use app\models\AlumnosServicioSocial;
use app\models\HorariosServicioSocial;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AlumnoHorariosSsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Inscripción a turnos';
$this->params['breadcrumbs'][] = $this->title;

$alumnos = AlumnosServicioSocial::find()
    ->where(['estado' => 1])
    ->orderBy(['id_alumno' => SORT_ASC])
    ->all();

$opcionesAlumnos = [];
foreach ($alumnos as $a) {
    $nombre = $a->usuario ? $a->usuario->getNombreCompleto() : 'Alumno #' . $a->id_alumno;
    $opcionesAlumnos[$a->id_alumno] = $nombre . ($a->matricula ? ' · ' . $a->matricula : '');
}

$turnos = HorariosServicioSocial::find()
    ->where(['estado' => 1])
    ->orderBy(['nombre_turno' => SORT_ASC, 'dia_semana' => SORT_ASC])
    ->all();

$opcionesTurnos = [];
foreach ($turnos as $t) {
    $opcionesTurnos[$t->id_horario_ss] = $t->nombre_turno
        . ' · ' . $t->dia_semana
        . ' · ' . substr($t->hora_inicio, 0, 5) . '-' . substr($t->hora_fin, 0, 5);
}
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Servicio Social</span>
            <h1 class="admin-page-title">Inscripción a turnos</h1>
            <p class="admin-page-subtitle">
                <?= number_format($dataProvider->getTotalCount()) ?> inscripciones registradas
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a(
                'Ir a Alumnos <i class="bi bi-arrow-right ms-1"></i>',
                ['/alumnos-servicio-social/index'],
                ['class' => 'btn-secondary-admin', 'encode' => false]
            ) ?>
            <?= Html::a(
                '<i class="bi bi-plus-lg me-2"></i>Inscribir alumno',
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
            'emptyText' => 'No hay inscripciones registradas.',
            'columns' => [
                [
                    'attribute' => 'id_alumno_horario',
                    'label' => 'ID',
                    'headerOptions' => ['style' => 'width: 70px'],
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'id_alumno',
                    'label' => 'Alumno',
                    'format' => 'raw',
                    'value' => function ($m) {
                        if (!$m->alumno) {
                            return '—';
                        }
                        $nombre = $m->alumno->usuario
                            ? $m->alumno->usuario->getNombreCompleto()
                            : 'Alumno #' . $m->alumno->id_alumno;
                        $matricula = $m->alumno->matricula
                            ? '<br><small class="text-muted">' . Html::encode($m->alumno->matricula) . '</small>'
                            : '';
                        return Html::encode($nombre) . $matricula;
                    },
                    'filter' => $opcionesAlumnos,
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'id_horario_ss',
                    'label' => 'Turno',
                    'format' => 'raw',
                    'value' => function ($m) {
                        if (!$m->horarioSs) {
                            return '—';
                        }
                        $t = $m->horarioSs;
                        return Html::encode($t->nombre_turno)
                            . '<br><small class="text-muted">'
                            . Html::encode($t->dia_semana)
                            . ' · ' . Html::encode(substr($t->hora_inicio, 0, 5))
                            . '-' . Html::encode(substr($t->hora_fin, 0, 5))
                            . '</small>';
                    },
                    'filter' => $opcionesTurnos,
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'fecha_inicio',
                    'label' => 'Inicio',
                    'format' => ['date', 'php:d/m/Y'],
                    'enableSorting' => false,
                    'filter' => false,
                ],
                [
                    'attribute' => 'fecha_fin',
                    'label' => 'Fin',
                    'format' => 'raw',
                    'value' => function ($m) {
                        if (empty($m->fecha_fin)) {
                            return '<span class="admin-muted-count">sin fecha</span>';
                        }
                        return Yii::$app->formatter->asDate($m->fecha_fin, 'php:d/m/Y');
                    },
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
                                    'confirm' => '¿Confirmas eliminar esta inscripción?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, AlumnoHorariosSs $model) {
                        return Url::to([$action, 'id_alumno_horario' => $model->id_alumno_horario]);
                    },
                ],
            ],
        ]); ?>
    </div>
</section>