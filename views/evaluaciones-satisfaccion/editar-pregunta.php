<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var app\models\PreguntasSatisfaccion $model */
/** @var string $opcionesTexto */
$this->title = 'Editar pregunta';
$this->params['breadcrumbs'][] = ['label' => 'Encuestas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Administrar preguntas', 'url' => ['preguntas']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Editar pregunta</h1>
            <p class="admin-page-subtitle">Los cambios se mostrarán en las nuevas encuestas; las respuestas anteriores se conservan.</p>
        </div>
    </header>

    <div class="admin-card p-4">
        <?php $form = ActiveForm::begin([
            'id' => 'editar-pregunta-form',
            'fieldConfig' => [
                'labelOptions' => ['class' => 'form-label'],
                'inputOptions' => ['class' => 'form-control'],
            ],
        ]); ?>

        <?= $form->field($model, 'pregunta')->textarea(['rows' => 3, 'maxlength' => true]) ?>

        <div class="mb-3">
            <label class="form-label">Tipo de pregunta</label>
            <input class="form-control" value="<?= Html::encode(ucfirst((string) $model->categoria)) ?>" disabled />
        </div>

        <?php if ($model->categoria === 'calificacion'): ?>
            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'escala_min')->input('number', ['min' => 1, 'max' => 10]) ?></div>
                <div class="col-md-6"><?= $form->field($model, 'escala_max')->input('number', ['min' => 2, 'max' => 10]) ?></div>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <?= Html::label('Opciones (una por línea)', 'opciones_texto', ['class' => 'form-label']) ?>
            <?= Html::textarea('opciones_texto', $opcionesTexto, [
                'id' => 'opciones_texto',
                'class' => 'form-control',
                'rows' => 6,
            ]) ?>
            <div class="form-text">En las preguntas de calificación, el número de opciones debe coincidir con la escala. En las demás, cada línea crea una opción seleccionable.</div>
            <?= Html::error($model, 'opciones_json', ['class' => 'invalid-feedback d-block']) ?>
        </div>

        <?= $form->field($model, 'estado')->dropDownList([1 => 'Activa', 0 => 'Inactiva'], ['class' => 'form-select']) ?>

        <div class="admin-form-actions">
            <?= Html::submitButton('Guardar cambios', ['class' => 'btn-primary-admin']) ?>
            <?= Html::a('Cancelar', ['preguntas'], ['class' => 'btn-cancel-admin']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</section>
