<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\HorariosPersonal $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="horarios-personal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_personal')->textInput() ?>

    <?= $form->field($model, 'dia_semana')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hora_inicio')->textInput() ?>

    <?= $form->field($model, 'hora_fin')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
