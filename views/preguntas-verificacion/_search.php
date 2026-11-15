<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PreguntasVerificacionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="preguntas-verificacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_pregunta') ?>

    <?= $form->field($model, 'pregunta') ?>

    <?= $form->field($model, 'categoria') ?>

    <?= $form->field($model, 'tipo_respuesta') ?>

    <?= $form->field($model, 'opciones_json') ?>

    <?php // echo $form->field($model, 'respuestas_reporte_json') ?>

    <?php // echo $form->field($model, 'orden') ?>

    <?php // echo $form->field($model, 'obligatoria') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
