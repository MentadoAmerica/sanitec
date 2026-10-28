<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Edificios $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="admin-form">
    <?php $form = ActiveForm::begin([
        'id' => 'edificios-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="row g-3">
        <div class="col-md-8">
            <?= $form->field($model, 'nombre')->textInput([
                'maxlength' => true,
                'placeholder' => 'Nombre o clave del edificio',
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'estado')->dropDownList(
                [1 => 'Activo', 0 => 'Inactivo'],
                ['class' => 'form-select']
            ) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'ubicacion')->textInput([
                'maxlength' => true,
                'placeholder' => 'Ubicación dentro del instituto',
            ]) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'descripcion')->textarea([
                'rows' => 4,
                'placeholder' => 'Información adicional',
            ]) ?>
        </div>
    </div>

    <div class="admin-form-actions">
        <?= Html::submitButton('Guardar', ['class' => 'btn-primary-admin']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn-cancel-admin']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>