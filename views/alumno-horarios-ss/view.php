<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\AlumnoHorariosSs $model */

$this->title = 'Inscripción #' . $model->id_alumno_horario;
$this->params['breadcrumbs'][] = ['label' => 'Inscripción a turnos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$alumno = $model->alumno;
$nombre = $alumno && $alumno->usuario
    ? $alumno->usuario->getNombreCompleto()
    : 'Alumno sin cuenta';
$turno = $model->horarioSs;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Inscripción #<?= Html::encode($model->id_alumno_horario) ?></span>
            <h1 class="admin-page-title"><?= Html::encode($nombre) ?></h1>
            <p class="admin-page-subtitle">
                <?php if ($turno): ?>
                    <?= Html::encode($turno->nombre_turno) ?>
                    · <?= Html::encode($turno->dia_semana) ?>
                    · <?= Html::encode(substr($turno->hora_inicio, 0, 5)) ?>-<?= Html::encode(substr($turno->hora_fin, 0, 5)) ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a('Editar', ['update', 'id_alumno_horario' => $model->id_alumno_horario], ['class' => 'btn-primary-admin']) ?>
            <?= Html::a('Volver al listado', ['index'], ['class' => 'btn-cancel-admin']) ?>
        </div>
    </header>

    <div class="admin-card">
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table admin-table admin-detail-table mb-0'],
            'attributes' => [
                [
                    'label' => 'Alumno',
                    'value' => $nombre,
                ],
                [
                    'label' => 'Matrícula',
                    'format' => 'raw',
                    'value' => $alumno && $alumno->matricula
                        ? '<code class="admin-code-chip">' . Html::encode($alumno->matricula) . '</code>'
                        : '<span class="admin-muted-count">sin matrícula</span>',
                ],
                [
                    'label' => 'Correo',
                    'value' => $alumno && $alumno->usuario ? $alumno->usuario->correo : '—',
                ],
                [
                    'label' => 'Turno',
                    'value' => $turno ? $turno->nombre_turno : '—',
                ],
                [
                    'label' => 'Día',
                    'value' => $turno ? $turno->dia_semana : '—',
                ],
                [
                    'label' => 'Horario',
                    'value' => $turno
                        ? substr($turno->hora_inicio, 0, 5) . ' - ' . substr($turno->hora_fin, 0, 5)
                        : '—',
                ],
                [
                    'attribute' => 'fecha_inicio',
                    'label' => 'Inicio',
                    'format' => ['date', 'php:d/m/Y'],
                ],
                [
                    'attribute' => 'fecha_fin',
                    'label' => 'Fin',
                    'format' => 'raw',
                    'value' => empty($model->fecha_fin)
                        ? '<span class="admin-muted-count">sin fecha de fin</span>'
                        : Yii::$app->formatter->asDate($model->fecha_fin, 'php:d/m/Y'),
                ],
                [
                    'attribute' => 'estado',
                    'label' => 'Estado',
                    'format' => 'raw',
                    'value' => $model->estado
                        ? '<span class="admin-badge admin-badge-active">Activo</span>'
                        : '<span class="admin-badge admin-badge-inactive">Inactivo</span>',
                ],
            ],
        ]); ?>
    </div>
</section>