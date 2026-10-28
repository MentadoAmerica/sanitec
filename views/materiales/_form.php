<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Materiales $model */
/** @var yii\bootstrap5\ActiveForm $form */

$esNuevo = $model->isNewRecord;
?>

<div class="admin-form">
    <?php $form = ActiveForm::begin([
        'id' => 'materiales-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="row g-3">
        <div class="col-md-8">
            <?= $form->field($model, 'nombre')->textInput([
                'maxlength' => true,
                'placeholder' => 'Papel higiénico, jabón líquido, cloro, etc.',
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'estado')->dropDownList(
                [1 => 'Activo', 0 => 'Inactivo'],
                ['class' => 'form-select']
            ) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'descripcion')->textarea([
                'rows' => 3,
                'placeholder' => 'Características del material',
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'unidad_medida')->dropDownList(
                [
                    'pieza' => 'Pieza',
                    'paquete' => 'Paquete',
                    'rollo' => 'Rollo',
                    'litro' => 'Litro',
                    'kilogramo' => 'Kilogramo',
                    'caja' => 'Caja',
                ],
                ['prompt' => 'Selecciona unidad', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'costo_unitario')->textInput([
                'type' => 'number',
                'step' => '0.01',
                'min' => '0',
                'placeholder' => '0.00',
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'stock_minimo')->textInput([
                'type' => 'number',
                'step' => '0.01',
                'min' => '0',
                'placeholder' => '0.00',
            ])->hint('Cantidad mínima antes de alertar reposición.') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'stock_actual')->textInput([
                'type' => 'number',
                'step' => '0.01',
                'min' => '0',
                'placeholder' => '0.00',
            ])->hint($esNuevo
                ? 'Stock inicial con el que se registra el material.'
                : 'Ajuste manual. En operación normal el stock cambia con los movimientos.'
            ) ?>
        </div>
    </div>

    <div class="admin-form-actions">
        <?= Html::submitButton('Guardar', ['class' => 'btn-primary-admin']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn-cancel-admin']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>