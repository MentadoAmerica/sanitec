<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\RondasProgramadas $model */
/** @var app\models\Sanitarios[] $sanitarios */
/** @var app\models\HorariosServicioSocial[] $horarios */

$this->title = 'Programar ronda';
$this->params['breadcrumbs'][] = ['label' => 'Rondas programadas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$opcionesSanitarios = [];
foreach ($sanitarios as $sanitario) {
    $opcionesSanitarios[$sanitario->id_sanitario] = $sanitario->nombre
        . ($sanitario->edificio ? ' · ' . $sanitario->edificio->nombre : '');
}
$opcionesHorarios = [];
foreach ($horarios as $horario) {
    $opcionesHorarios[$horario->id_horario_ss] = $horario->nombre_turno
        . ' · ' . $horario->dia_semana
        . ' · ' . substr($horario->hora_inicio, 0, 5)
        . '–' . substr($horario->hora_fin, 0, 5);
}
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">ADMINISTRACIÓN · SERVICIO SOCIAL</span>
            <h1 class="admin-page-title">Programar ronda de verificación</h1>
            <p class="admin-page-subtitle">Asigna un sanitario, un horario y el momento de revisión.</p>
        </div>
    </header>

    <div class="admin-card p-4">
        <?php $form = ActiveForm::begin(['id' => 'ronda-programada-form']); ?>
        <div class="row g-3">
            <div class="col-md-6">
                <?= $form->field($model, 'id_sanitario')->dropDownList($opcionesSanitarios, [
                    'prompt' => 'Selecciona un sanitario',
                    'class' => 'form-select',
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'id_horario_ss')->dropDownList($opcionesHorarios, [
                    'prompt' => 'Selecciona un horario de servicio social',
                    'class' => 'form-select',
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'fecha_programada')->input('date', ['class' => 'form-control']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'hora_programada')->input('time', ['class' => 'form-control']) ?>
            </div>
        </div>
        <div class="admin-form-actions mt-4">
            <?= Html::submitButton('Guardar ronda', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</section>
