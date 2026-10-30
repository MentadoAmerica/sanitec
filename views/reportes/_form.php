<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\TiposReporte;
use app\models\Sanitarios;

/** @var yii\web\View $this */
/** @var app\models\Reportes $model */
/** @var yii\bootstrap5\ActiveForm $form */

$tipos = TiposReporte::find()
    ->where(['estado' => 1])
    ->orderBy(['nombre' => SORT_ASC])
    ->select(['nombre', 'id_tipo_reporte', 'prioridad'])
    ->asArray()
    ->all();

$opcionesTipos = [];
$prioridadPorTipo = [];
foreach ($tipos as $t) {
    $opcionesTipos[$t['id_tipo_reporte']] = $t['nombre'];
    $prioridadPorTipo[$t['id_tipo_reporte']] = $t['prioridad'];
}

$sanitarios = Sanitarios::find()
    ->where(['estado' => 1])
    ->orderBy(['nombre' => SORT_ASC])
    ->all();

$opcionesSanitarios = [];
foreach ($sanitarios as $s) {
    $edificio = $s->edificio ? ' · ' . $s->edificio->nombre : '';
    $opcionesSanitarios[$s->id_sanitario] = $s->nombre . $edificio;
}
?>

<div class="admin-form">
    <?php $form = ActiveForm::begin([
        'id' => 'reportes-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="row g-3">
        <div class="col-md-6">
            <?= $form->field($model, 'id_sanitario')->dropDownList(
                $opcionesSanitarios,
                ['prompt' => 'Selecciona un sanitario', 'class' => 'form-select']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'id_tipo_reporte')->dropDownList(
                $opcionesTipos,
                ['prompt' => 'Selecciona un tipo', 'class' => 'form-select', 'id' => 'tipo-reporte-select']
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'prioridad')->dropDownList(
                [
                    'baja' => 'Baja',
                    'normal' => 'Normal',
                    'alta' => 'Alta',
                    'urgente' => 'Urgente',
                ],
                ['prompt' => 'Selecciona prioridad', 'class' => 'form-select', 'id' => 'prioridad-select']
            )->hint('Se sugiere automáticamente según el tipo, pero puedes ajustarla.') ?>
        </div>
        <div class="col-md-8">
            <?= $form->field($model, 'evidencia_foto')->textInput([
                'maxlength' => true,
                'placeholder' => 'URL o referencia de la foto (opcional)',
                'readonly' => $model->id_verificacion !== null,
            ])->hint(
                $model->id_verificacion !== null
                    ? 'Se adjunta la fotografía registrada durante la verificación.'
                    : 'URL o referencia de la foto (opcional).'
            ) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'descripcion')->textarea([
                'rows' => 4,
                'placeholder' => 'Describe el problema detectado',
            ]) ?>
        </div>
    </div>

    <div class="admin-form-actions">
        <?= Html::submitButton('Guardar reporte', ['class' => 'btn-primary-admin']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn-cancel-admin']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$prioridadJson = json_encode($prioridadPorTipo);
$this->registerJs(<<<JS
(function () {
    var mapa = {$prioridadJson};
    var tipo = document.getElementById('tipo-reporte-select');
    var prioridad = document.getElementById('prioridad-select');
    if (!tipo || !prioridad) return;
    tipo.addEventListener('change', function () {
        var sugerida = mapa[this.value];
        if (sugerida) {
            prioridad.value = sugerida;
        }
    });
})();
JS
);
?>