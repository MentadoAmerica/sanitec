<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AlumnosServicioSocial|null $alumno */
/** @var app\models\Verificaciones[] $verificaciones */

$this->title = 'Mis verificaciones';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Servicio Social</span>
            <h1 class="admin-page-title">Mis verificaciones</h1>
            <p class="admin-page-subtitle">
                Rondas realizadas y resultados registrados.
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a(
                '<i class="bi bi-signpost-split me-1"></i>Rondas del día',
                ['/rondas-programadas/index'],
                ['class' => 'btn-secondary-admin', 'encode' => false]
            ) ?>
        </div>
    </header>

    <?php if ($alumno === null): ?>
        <div class="admin-alert admin-alert-warning">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Tu cuenta no está vinculada a un registro de alumno.</strong>
                Contacta al administrador para que complete tu registro con matrícula y PIN.
            </div>
        </div>
    <?php elseif (empty($verificaciones)): ?>
        <div class="admin-card">
            <div class="admin-empty-state">
                <i class="bi bi-clipboard" aria-hidden="true"></i>
                <span>Aún no has registrado verificaciones.</span>
                <?= Html::a(
                    'Ir a rondas del día',
                    ['/rondas-programadas/index'],
                    ['class' => 'btn-secondary-admin mt-3']
                ) ?>
            </div>
        </div>
    <?php else: ?>
        <div class="admin-card">
            <?= \yii\grid\GridView::widget([
                'dataProvider' => new \yii\data\ArrayDataProvider([
                    'allModels' => $verificaciones,
                    'pagination' => ['pageSize' => 20],
                ]),
                'tableOptions' => ['class' => 'table admin-table align-middle mb-0'],
                'headerRowOptions' => ['class' => 'admin-table-head'],
                'summary' => 'Mostrando {begin} a {end} de {total} registros',
                'columns' => [
                    [
                        'label' => 'Inicio',
                        'value' => function ($m) {
                            return Yii::$app->formatter->asDatetime($m->fecha_hora_inicio, 'php:d/m/Y H:i');
                        },
                    ],
                    [
                        'label' => 'Edificio',
                        'value' => function ($m) {
                            return $m->sanitario && $m->sanitario->edificio
                                ? $m->sanitario->edificio->nombre
                                : '—';
                        },
                    ],
                    [
                        'label' => 'Sanitario',
                        'value' => function ($m) {
                            return $m->sanitario ? $m->sanitario->nombre : '—';
                        },
                    ],
                    [
                        'label' => 'Resultado',
                        'value' => function ($m) {
                            return $m->resultado_general ?: 'Pendiente';
                        },
                    ],
                    [
                        'label' => 'Reportes',
                        'format' => 'raw',
                        'value' => function ($m) {
                            $total = (int) \app\models\Reportes::find()
                                ->where(['id_verificacion' => $m->id_verificacion])
                                ->count();
                            if ($total === 0) {
                                return '<span class="admin-muted-count">sin reportes</span>';
                            }
                            return '<span class="admin-count-chip">' . $total . '</span>';
                        },
                    ],
                    [
                        'label' => 'Estado',
                        'format' => 'raw',
                        'value' => function ($m) {
                            return $m->fecha_hora_fin === null
                                ? '<span class="admin-badge admin-badge-warning">En curso</span>'
                                : '<span class="admin-badge admin-badge-active">Finalizada</span>';
                        },
                    ],
                    [
                        'label' => '',
                        'format' => 'raw',
                        'value' => function ($m) {
                            return Html::a(
                                'Ver',
                                ['view', 'id_verificacion' => $m->id_verificacion],
                                ['class' => 'btn-action-view']
                            );
                        },
                        'headerOptions' => ['style' => 'width:80px;text-align:right'],
                        'contentOptions' => ['class' => 'text-end'],
                    ],
                ],
            ]) ?>
        </div>
    <?php endif; ?>
</section>