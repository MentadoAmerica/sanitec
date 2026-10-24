<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Materiales $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="materiales-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'unidad_medida')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stock_actual')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stock_minimo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'costo_unitario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
