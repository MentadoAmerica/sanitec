<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AsignacionesPersonal $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="asignaciones-personal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_personal')->textInput() ?>

    <?= $form->field($model, 'id_edificio')->textInput() ?>

    <?= $form->field($model, 'fecha_inicio')->textInput() ?>

    <?= $form->field($model, 'fecha_fin')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
