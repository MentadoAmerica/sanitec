<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\HistorialReportes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="historial-reportes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_reporte')->textInput() ?>

    <?= $form->field($model, 'id_usuario')->textInput() ?>

    <?= $form->field($model, 'estado_anterior')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estado_nuevo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_hora')->textInput() ?>

    <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
