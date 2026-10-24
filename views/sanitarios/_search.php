<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SanitariosSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sanitarios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_sanitario') ?>

    <?= $form->field($model, 'id_edificio') ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'tipo') ?>

    <?= $form->field($model, 'ubicacion') ?>

    <?php // echo $form->field($model, 'codigo_qr') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
