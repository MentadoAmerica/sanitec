<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\HistorialReportesSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="historial-reportes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_historial') ?>

    <?= $form->field($model, 'id_reporte') ?>

    <?= $form->field($model, 'id_usuario') ?>

    <?= $form->field($model, 'estado_anterior') ?>

    <?= $form->field($model, 'estado_nuevo') ?>

    <?php // echo $form->field($model, 'fecha_hora') ?>

    <?php // echo $form->field($model, 'observaciones') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
