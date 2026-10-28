<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\TiposReporte $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="admin-form">
    <?php $form = ActiveForm::begin([
        'id' => 'tipos-reporte-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="row g-3">
        <div class="col-md-6">
            <?= $form->field($model, 'nombre')->textInput([
                'maxlength' => true,
                'placeholder' => 'Falta de papel, fuga, obstrucción, etc.',
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'categoria')->dropDownList(
                [
                    'limpieza' => 'Limpieza',
                    'suministros' => 'Suministros',
                    'mantenimiento' => 'Mantenimiento',
                ],
                ['prompt' => 'Selecciona categoría', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'prioridad')->dropDownList(
                [
                    'baja' => 'Baja',
                    'normal' => 'Normal',
                    'alta' => 'Alta',
                    'urgente' => 'Urgente',
                ],
                ['prompt' => 'Selecciona prioridad', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-3">
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