<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\Usuarios;
use app\models\AlumnosServicioSocial;

/** @var yii\web\View $this */
/** @var app\models\AlumnosServicioSocial $model */
/** @var yii\bootstrap5\ActiveForm $form */

$idsYaVinculados = AlumnosServicioSocial::find()
    ->select('id_usuario')
    ->where(['IS NOT', 'id_usuario', null])
    ->andWhere(['NOT', ['id_usuario' => $model->id_usuario ?: 0]])
    ->column();

$usuariosDisponibles = Usuarios::find()
    ->where(['estado' => 1, 'id_rol' => 2])
    ->andWhere(['NOT IN', 'id_usuario', $idsYaVinculados])
    ->orderBy(['nombre' => SORT_ASC])
    ->all();

$opcionesUsuarios = [];
foreach ($usuariosDisponibles as $u) {
    $opcionesUsuarios[$u->id_usuario] = $u->getNombreCompleto() . ' · ' . $u->correo;
}

$hayUsuariosSinAsignar = count($usuariosDisponibles) > 0;
?>

<div class="admin-form">
    <?php if (!$hayUsuariosSinAsignar && $model->isNewRecord): ?>
        <div class="admin-alert admin-alert-warning">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>No hay cuentas disponibles.</strong>
                Primero crea la cuenta del alumno en
                <?= Html::a('Usuarios', ['/usuarios/index'], ['class' => 'fw-semibold']) ?>
                con rol <em>Servicio Social</em>, luego regresa aquí a vincularla.
            </div>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin([
        'id' => 'alumnos-servicio-social-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="row g-3">
        <div class="col-md-8">
            <?= $form->field($model, 'id_usuario')->dropDownList(
                $opcionesUsuarios,
                [
                    'prompt' => 'Selecciona una cuenta',
                    'class' => 'form-select',
                    'disabled' => !$hayUsuariosSinAsignar && $model->isNewRecord,
                ]
            )->hint($model->isNewRecord
                ? 'Solo aparecen usuarios activos con rol Servicio Social que aún no estén vinculados.'
                : 'Cambia la cuenta si el alumno original ya no participa. Al guardar se actualiza la vinculación.'
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'estado')->dropDownList(
                [1 => 'Activo', 0 => 'Inactivo'],
                ['class' => 'form-select']
            )->hint('Inactivo = ya no participa pero conserva historial.') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'matricula')->textInput([
                'maxlength' => true,
                'placeholder' => 'Número de control del alumno',
            ])->hint('Opcional. Debe ser única en el sistema si la capturas.') ?>
        </div>
    </div>

    <div class="admin-form-actions">
        <?= Html::submitButton('Guardar', ['class' => 'btn-primary-admin']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn-cancel-admin']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>