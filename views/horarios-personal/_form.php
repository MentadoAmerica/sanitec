<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\Personal;

/** @var yii\web\View $this */
/** @var app\models\HorariosPersonal $model */
/** @var yii\bootstrap5\ActiveForm $form */

$personalList = Personal::find()
    ->where(['estado' => 1])
    ->orderBy(['id_personal' => SORT_ASC])
    ->all();

$opcionesPersonal = [];
foreach ($personalList as $p) {
    $nombre = $p->usuario
        ? $p->usuario->getNombreCompleto()
        : ucfirst($p->tipo_personal) . ' sin cuenta';
    $opcionesPersonal[$p->id_personal] = $nombre;
}

$dias = [
    'Lunes' => 'Lunes',
    'Martes' => 'Martes',
    'Miércoles' => 'Miércoles',
    'Jueves' => 'Jueves',
    'Viernes' => 'Viernes',
    'Sábado' => 'Sábado',
    'Domingo' => 'Domingo',
];
?>

<div class="admin-form">
    <?php $form = ActiveForm::begin([
        'id' => 'horarios-personal-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="row g-3">
        <div class="col-md-6">
            <?= $form->field($model, 'id_personal')->dropDownList(
                $opcionesPersonal,
                ['prompt' => 'Selecciona al trabajador', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'dia_semana')->dropDownList(
                $dias,
                ['prompt' => 'Selecciona un día', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'hora_inicio')->input('time') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'hora_fin')->input('time') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'estado')->dropDownList(
                [1 => 'Activo', 0 => 'Inactivo'],
                ['class' => 'form-select']
            ) ?>
        </div>
    </div>

    <div class="admin-form-actions">
        <?= Html::submitButton('Guardar', ['class' => 'btn-primary-admin']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn-cancel-admin']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>