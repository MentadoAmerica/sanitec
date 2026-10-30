<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MovimientosInventario $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="movimientos-inventario-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_material')->textInput() ?>

    <?= $form->field($model, 'id_usuario_registra')->textInput() ?>

    <?= $form->field($model, 'id_personal_receptor')->textInput() ?>

    <?= $form->field($model, 'id_edificio')->textInput() ?>

    <?= $form->field($model, 'id_sanitario')->textInput() ?>

    <?= $form->field($model, 'tipo_movimiento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cantidad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'costo_unitario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'costo_total')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_hora')->textInput() ?>

    <?= $form->field($model, 'proveedor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'folio_documento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
