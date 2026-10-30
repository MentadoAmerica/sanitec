<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Verificaciones $model */
/** @var array $checklist */
/** @var array $aspectos */
/** @var array $erroresFormulario */

$this->title = 'Finalizar ronda';
$this->params['breadcrumbs'][] = ['label' => 'Mis rondas', 'url' => ['/rondas-programadas/index']];
$this->params['breadcrumbs'][] = $this->title;
$opciones = [
    'cumple' => 'Cumple',
    'requiere_atencion' => 'Requiere atención',
    'no_aplica' => 'No aplica',
];
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Ronda en curso</span>
            <h1 class="admin-page-title">Verificar condiciones del sanitario</h1>
            <p class="admin-page-subtitle">
                Selecciona un resultado para cada punto. Si detectas un problema, podrás generar un reporte al finalizar.
            </p>
        </div>
    </header>

    <?php if ($erroresFormulario !== []): ?>
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                <?php foreach ($erroresFormulario as $error): ?>
                    <li><?= Html::encode($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="admin-card p-4">
        <?php $form = ActiveForm::begin([
            'id' => 'verificacion-form',
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        <div class="row g-3">
            <?php foreach ($aspectos as $key => $label): ?>
                <fieldset class="col-12 col-lg-6">
                    <legend class="fs-6 fw-semibold"><?= Html::encode($label) ?></legend>
                    <div class="d-flex flex-wrap gap-3">
                        <?php foreach ($opciones as $valor => $texto): ?>
                            <div class="form-check">
                                <?= Html::radio('checklist[' . $key . ']', ($checklist[$key] ?? '') === $valor, [
                                    'value' => $valor,
                                    'class' => 'form-check-input',
                                    'id' => 'check-' . $key . '-' . $valor,
                                    'required' => true,
                                ]) ?>
                                <?= Html::label($texto, 'check-' . $key . '-' . $valor, ['class' => 'form-check-label']) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
            <?php endforeach; ?>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-12">
                <label for="observaciones" class="form-label">Observaciones</label>
                <?= Html::textarea('observaciones', $model->observaciones, [
                    'id' => 'observaciones',
                    'class' => 'form-control',
                    'rows' => 4,
                    'maxlength' => 5000,
                    'placeholder' => 'Describe cualquier detalle relevante o acción realizada.',
                ]) ?>
            </div>
            <div class="col-12 col-md-8">
                <label for="evidencia" class="form-label">Evidencia fotográfica (opcional)</label>
                <?= Html::fileInput('evidencia', null, [
                    'id' => 'evidencia',
                    'class' => 'form-control',
                    'accept' => 'image/jpeg,image/png,image/webp',
                    'capture' => 'environment',
                ]) ?>
                <div class="form-text">JPG, PNG o WEBP; máximo 5 MB.</div>
            </div>
        </div>

        <div class="admin-form-actions mt-4">
            <?= Html::submitButton('Guardar y finalizar ronda', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Volver a mis rondas', ['/rondas-programadas/index'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</section>
