<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\Materiales;
use app\models\Personal;
use app\models\Edificios;

/** @var yii\web\View $this */
/** @var app\models\MovimientosInventario $model */

$materiales = Materiales::find()
    ->where(['estado' => 1])
    ->orderBy(['nombre' => SORT_ASC])
    ->select(['nombre', 'id_material'])
    ->indexBy('id_material')
    ->column();

$personal = Personal::find()->where(['estado' => 1])->all();
$opcionesPersonal = ['' => 'Sin receptor específico'];
foreach ($personal as $p) {
    $nombre = $p->usuario ? $p->usuario->getNombreCompleto() : ucfirst($p->tipo_personal) . ' · ' . $p->identificador;
    $opcionesPersonal[$p->id_personal] = $nombre;
}

$edificios = Edificios::find()
    ->where(['estado' => 1])
    ->orderBy(['nombre' => SORT_ASC])
    ->select(['nombre', 'id_edificio'])
    ->indexBy('id_edificio')
    ->column();
?>

<div class="admin-form">
    <?php $form = ActiveForm::begin([
        'id' => 'salida-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <input type="hidden" name="tipo" value="salida">
    <?= $form->field($model, 'tipo_movimiento')->hiddenInput()->label(false) ?>

    <div class="row g-3">
        <div class="col-md-6">
            <?= $form->field($model, 'id_material')->dropDownList(
                $materiales,
                ['prompt' => 'Selecciona un material', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'cantidad')->textInput([
                'type' => 'number', 'step' => '0.01', 'min' => '0.01',
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'id_personal_receptor')->dropDownList(
                $opcionesPersonal,
                ['class' => 'form-select']
            )->hint('Persona que recibe el material. Opcional si solo se destina a un edificio.') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'id_edificio')->dropDownList(
                $edificios,
                ['prompt' => 'Sin edificio específico', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'observaciones')->textarea(['rows' => 3]) ?>
        </div>
    </div>

    <div class="admin-form-actions">
        <?= Html::submitButton('Registrar entrega', ['class' => 'btn-primary-admin']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn-cancel-admin']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>