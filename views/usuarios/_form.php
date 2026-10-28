<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\Roles;

/** @var yii\web\View $this */
/** @var app\models\Usuarios $model */
/** @var yii\bootstrap5\ActiveForm $form */

$roles = Roles::find()
    ->where(['estado' => 1])
    ->orderBy(['nombre' => SORT_ASC])
    ->select(['nombre', 'id_rol'])
    ->indexBy('id_rol')
    ->column();

$esNuevo = $model->isNewRecord;
?>

<div class="admin-form">
    <?php $form = ActiveForm::begin([
        'id' => 'usuarios-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="row g-3">
        <div class="col-md-6">
            <?= $form->field($model, 'nombre')->textInput([
                'maxlength' => true,
                'placeholder' => 'Nombre del usuario',
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'apellidos')->textInput([
                'maxlength' => true,
                'placeholder' => 'Apellidos del usuario',
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'correo')->textInput([
                'maxlength' => true,
                'type' => 'email',
                'placeholder' => 'usuario@itmatamoros.edu.mx',
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'telefono')->textInput([
                'maxlength' => true,
                'placeholder' => 'Opcional',
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'id_rol')->dropDownList(
                $roles,
                ['prompt' => 'Selecciona un rol', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'estado')->dropDownList(
                [1 => 'Activo', 0 => 'Inactivo'],
                ['class' => 'form-select']
            ) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'contrasena')->passwordInput([
                'autocomplete' => 'new-password',
                'placeholder' => $esNuevo ? 'Contraseña del usuario' : 'Dejar en blanco para conservar la actual',
            ])->hint($esNuevo
                ? 'La contraseña se guardará cifrada en la base de datos.'
                : 'Solo escribe una nueva contraseña si deseas cambiarla.'
            ) ?>
        </div>
    </div>

    <div class="admin-form-actions">
        <?= Html::submitButton('Guardar', ['class' => 'btn-primary-admin']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn-cancel-admin']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>