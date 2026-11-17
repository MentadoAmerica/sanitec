<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\RondasProgramadas $model */
/** @var app\models\HorariosServicioSocial[] $turnos */
/** @var app\models\Sanitarios[] $sanitarios */

$this->title = 'Editar ronda';
$this->params['breadcrumbs'][] = ['label' => 'Rondas del día', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Servicio Social</span>
            <h1 class="admin-page-title">Editar ronda #<?= Html::encode($model->id_ronda) ?></h1>
        </div>
    </header>

    <div class="admin-card">
        <?php $form = ActiveForm::begin([
            'id' => 'ronda-update-form',
            'fieldConfig' => [
                'labelOptions' => ['class' => 'form-label'],
                'inputOptions' => ['class' => 'form-control'],
            ],
        ]); ?>

        <div class="row g-3">
            <div class="col-md-6">
                <?= $form->field($model, 'id_sanitario')->dropDownList(
                    \yii\helpers\ArrayHelper::map($sanitarios, 'id_sanitario', 'nombre'),
                    ['prompt' => 'Selecciona un sanitario', 'class' => 'form-select']
                ) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'id_horario_ss')->dropDownList(
                    \yii\helpers\ArrayHelper::map($turnos, 'id_horario_ss', function ($t) {
                        return $t->nombre_turno . ' · ' . $t->dia_semana;
                    }),
                    ['prompt' => 'Selecciona un turno', 'class' => 'form-select']
                ) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'fecha_programada')->input('date') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'hora_programada')->input('time') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'estado')->dropDownList(
                    [
                        'Pendiente' => 'Pendiente',
                        'Programada' => 'Programada',
                        'Completada' => 'Completada',
                        'No realizada' => 'No realizada',
                    ],
                    ['class' => 'form-select']
                ) ?>
            </div>
        </div>

        <div class="admin-form-actions">
            <?= Html::a('Cancelar', ['index'], ['class' => 'btn-cancel-admin']) ?>
            <?= Html::submitButton('Guardar', ['class' => 'btn-primary-admin']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</section>