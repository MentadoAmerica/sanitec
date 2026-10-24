<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\HorariosPersonalSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="horarios-personal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_horario') ?>

    <?= $form->field($model, 'id_personal') ?>

    <?= $form->field($model, 'dia_semana') ?>

    <?= $form->field($model, 'hora_inicio') ?>

    <?= $form->field($model, 'hora_fin') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
