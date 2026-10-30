<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\Materiales;

/** @var yii\web\View $this */
/** @var app\models\MovimientosInventario $model */

$materiales = Materiales::find()
    ->where(['estado' => 1])
    ->orderBy(['nombre' => SORT_ASC])
    ->select(['nombre', 'id_material'])
    ->indexBy('id_material')
    ->column();
?>

<div class="admin-form">
    <?php $form = ActiveForm::begin([
        'id' => 'entrada-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <input type="hidden" name="tipo" value="entrada">
    <?= $form->field($model, 'tipo_movimiento')->hiddenInput()->label(false) ?>

    <div class="row g-3">
        <div class="col-md-6">
            <?= $form->field($model, 'id_material')->dropDownList(
                $materiales,
                ['prompt' => 'Selecciona un material', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'cantidad')->textInput([
                'type' => 'number', 'step' => '0.01', 'min' => '0.01',
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'costo_unitario')->textInput([
                'type' => 'number', 'step' => '0.01', 'min' => '0',
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'proveedor')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'folio_documento')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'observaciones')->textarea(['rows' => 3]) ?>
        </div>
    </div>

    <div class="admin-form-actions">
        <?= Html::submitButton('Registrar entrada', ['class' => 'btn-primary-admin']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn-cancel-admin']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>