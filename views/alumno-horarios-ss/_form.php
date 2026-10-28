<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\AlumnosServicioSocial;
use app\models\HorariosServicioSocial;

/** @var yii\web\View $this */
/** @var app\models\AlumnoHorariosSs $model */
/** @var yii\bootstrap5\ActiveForm $form */

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

<div class="admin-form">
    <?php $form = ActiveForm::begin([
        'id' => 'alumno-horarios-ss-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="row g-3">
        <div class="col-md-6">
            <?= $form->field($model, 'id_alumno')->dropDownList(
                $opcionesAlumnos,
                ['prompt' => 'Selecciona un alumno', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'id_horario_ss')->dropDownList(
                $opcionesTurnos,
                ['prompt' => 'Selecciona un turno', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'fecha_inicio')->input('date') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'fecha_fin')->input('date')
                ->hint('Opcional. Dejar en blanco si la inscripción sigue vigente.') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'estado')->dropDownList(
                [1 => 'Activo', 0 => 'Inactivo'],
                ['class' => 'form-select']
            ) ?>
        </div>
    </div>

    <div class="admin-form-actions">
        <?= Html::submitButton('Guardar', ['class' => 'btn-primary-admin']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn-cancel-admin']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>