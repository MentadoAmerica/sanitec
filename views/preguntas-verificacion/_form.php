<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PreguntasVerificacion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="preguntas-verificacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pregunta')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'categoria')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tipo_respuesta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'opciones_json')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'respuestas_reporte_json')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'orden')->textInput() ?>

    <?= $form->field($model, 'obligatoria')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
