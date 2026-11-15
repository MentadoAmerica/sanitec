<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\RespuestasVerificacionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="respuestas-verificacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_respuesta') ?>

    <?= $form->field($model, 'id_verificacion') ?>

    <?= $form->field($model, 'id_pregunta') ?>

    <?= $form->field($model, 'respuesta') ?>

    <?= $form->field($model, 'observacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
