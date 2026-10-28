<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ReportesSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="reportes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_reporte') ?>

    <?= $form->field($model, 'id_sanitario') ?>

    <?= $form->field($model, 'id_tipo_reporte') ?>

    <?= $form->field($model, 'id_verificacion') ?>

    <?= $form->field($model, 'id_usuario_reporta') ?>

    <?php // echo $form->field($model, 'fecha_hora_reporte') ?>

    <?php // echo $form->field($model, 'descripcion') ?>

    <?php // echo $form->field($model, 'evidencia_foto') ?>

    <?php // echo $form->field($model, 'prioridad') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'fecha_cierre') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
