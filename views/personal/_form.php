<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\Usuarios;
use app\models\Personal;

/** @var yii\web\View $this */
/** @var app\models\Personal $model */
/** @var yii\bootstrap5\ActiveForm $form */

$idsVinculados = Personal::find()
    ->select('id_usuario')
    ->where(['IS NOT', 'id_usuario', null])
    ->andWhere(['NOT', ['id_usuario' => $model->id_usuario ?: 0]])
    ->column();

$usuariosDisponibles = Usuarios::find()
    ->where(['estado' => 1])
    ->andWhere(['NOT IN', 'id_usuario', $idsVinculados])
    ->orderBy(['nombre' => SORT_ASC])
    ->all();

$opcionesUsuarios = ['' => 'Sin cuenta web (solo tarjeta)'];
foreach ($usuariosDisponibles as $u) {
    $opcionesUsuarios[$u->id_usuario] = trim($u->nombre . ' ' . $u->apellidos);
}
?>

<div class="admin-form">
    <?php $form = ActiveForm::begin([
        'id' => 'personal-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="row g-3">
        <div class="col-md-6">
            <?= $form->field($model, 'tipo_personal')->textInput([
                'maxlength' => true,
                'placeholder' => 'intendente, supervisor, encargado',
            ])->hint('Tipo de personal que se registrará en el sistema.') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'estado')->dropDownList(
                [1 => 'Activo', 0 => 'Inactivo'],
                ['class' => 'form-select']
            ) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'identificador')->textInput([
                'maxlength' => true,
                'placeholder' => 'Número de tarjeta institucional',
            ])->hint('Es el identificador que se leerá al registrar entrada y salida del servicio.') ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'id_usuario')->dropDownList(
                $opcionesUsuarios,
                ['class' => 'form-select']
            )->hint('Opcional. Los intendentes no necesitan cuenta para usar el lector de tarjetas.') ?>
        </div>
    </div>

    <div class="admin-form-actions">
        <?= Html::submitButton('Guardar', ['class' => 'btn-primary-admin']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn-cancel-admin']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>