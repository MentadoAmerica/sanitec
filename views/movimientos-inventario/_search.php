<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MovimientosInventarioSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="movimientos-inventario-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_movimiento') ?>

    <?= $form->field($model, 'id_material') ?>

    <?= $form->field($model, 'id_usuario_registra') ?>

    <?= $form->field($model, 'id_personal_receptor') ?>

    <?= $form->field($model, 'id_edificio') ?>

    <?php // echo $form->field($model, 'id_sanitario') ?>

    <?php // echo $form->field($model, 'tipo_movimiento') ?>

    <?php // echo $form->field($model, 'cantidad') ?>

    <?php // echo $form->field($model, 'costo_unitario') ?>

    <?php // echo $form->field($model, 'costo_total') ?>

    <?php // echo $form->field($model, 'fecha_hora') ?>

    <?php // echo $form->field($model, 'proveedor') ?>

    <?php // echo $form->field($model, 'folio_documento') ?>

    <?php // echo $form->field($model, 'observaciones') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
