<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\HorariosServicioSocial $model */
/** @var yii\bootstrap5\ActiveForm $form */

$dias = [
    'Lunes' => 'Lunes',
    'Martes' => 'Martes',
    'Miércoles' => 'Miércoles',
    'Jueves' => 'Jueves',
    'Viernes' => 'Viernes',
    'Sábado' => 'Sábado',
    'Domingo' => 'Domingo',
];

$turnosComunes = [
    'Matutino' => 'Matutino',
    'Vespertino' => 'Vespertino',
    'Nocturno' => 'Nocturno',
];
?>

<div class="admin-form">
    <?php $form = ActiveForm::begin([
        'id' => 'horarios-servicio-social-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="row g-3">
        <div class="col-md-6">
            <?= $form->field($model, 'nombre_turno')->textInput([
                'maxlength' => true,
                'list' => 'turnos-comunes',
                'placeholder' => 'Matutino, Vespertino, etc.',
            ])->hint('Puedes escribir un nombre nuevo o elegir uno existente.') ?>
            <datalist id="turnos-comunes">
                <?php foreach ($turnosComunes as $t): ?>
                    <option value="<?= Html::encode($t) ?>"></option>
                <?php endforeach; ?>
            </datalist>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'dia_semana')->dropDownList(
                $dias,
                ['prompt' => 'Selecciona un día', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'hora_inicio')->input('time') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'hora_fin')->input('time') ?>
        </div>
        <div class="col-md-6">
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