<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\RespuestasVerificacion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="respuestas-verificacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_verificacion')->textInput() ?>

    <?= $form->field($model, 'id_pregunta')->textInput() ?>

    <?= $form->field($model, 'respuesta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
