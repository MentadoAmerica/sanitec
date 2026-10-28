<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\Edificios;

/** @var yii\web\View $this */
/** @var app\models\Sanitarios $model */
/** @var yii\bootstrap5\ActiveForm $form */

$edificios = Edificios::find()
    ->where(['estado' => 1])
    ->orderBy(['nombre' => SORT_ASC])
    ->select(['nombre', 'id_edificio'])
    ->indexBy('id_edificio')
    ->column();
?>

<div class="admin-form">
    <?php $form = ActiveForm::begin([
        'id' => 'sanitarios-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="row g-3">
        <div class="col-md-4">
            <?= $form->field($model, 'id_edificio')->dropDownList(
                $edificios,
                ['prompt' => 'Selecciona un edificio', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'nombre')->textInput([
                'maxlength' => true,
                'placeholder' => 'Por ejemplo R-01',
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'tipo')->textInput([
                'maxlength' => true,
                'placeholder' => 'Hombres, Mujeres, Mixto',
            ]) ?>
        </div>
        <div class="col-md-8">
            <?= $form->field($model, 'ubicacion')->textInput([
                'maxlength' => true,
                'placeholder' => 'Ubicación específica dentro del edificio',
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'estado')->dropDownList(
                [1 => 'Activo', 0 => 'Inactivo'],
                ['class' => 'form-select']
            ) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'codigo_qr')->textInput([
                'maxlength' => true,
                'placeholder' => 'Código único del QR',
            ])->hint('Este código identifica al sanitario cuando alguien escanea el QR.') ?>
        </div>
    </div>

    <div class="admin-form-actions">
        <?= Html::submitButton('Guardar', ['class' => 'btn-primary-admin']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn-cancel-admin']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>