<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ServiciosSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="servicios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_servicio') ?>

    <?= $form->field($model, 'id_personal') ?>

    <?= $form->field($model, 'id_sanitario') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'hora_checkin') ?>

    <?php // echo $form->field($model, 'hora_checkout') ?>

    <?php // echo $form->field($model, 'observaciones') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
