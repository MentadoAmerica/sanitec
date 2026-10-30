<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SolicitudesAbastecimiento $model */
/** @var app\models\Reportes $reporte */
/** @var app\models\Materiales[] $materiales */

$this->title = 'Solicitar insumos';
$this->params['breadcrumbs'][] = ['label' => 'Solicitudes de insumos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$opcionesMateriales = [];
foreach ($materiales as $material) {
    $opcionesMateriales[$material->id_material] = $material->nombre
        . ' · ' . $material->unidad_medida
        . ' · Existencia: ' . $material->stock_actual;
}
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">SEGUIMIENTO DE INCIDENCIA</span>
            <h1 class="admin-page-title">Solicitar abastecimiento</h1>
            <p class="admin-page-subtitle">
                Reporte #<?= Html::encode($reporte->id_reporte) ?> ·
                <?= Html::encode($reporte->sanitario ? $reporte->sanitario->nombre : 'Sanitario') ?>
            </p>
        </div>
    </header>

    <?php if ($opcionesMateriales === []): ?>
        <div class="alert alert-warning">No hay materiales activos en el catálogo para solicitar.</div>
    <?php else: ?>
        <div class="admin-card p-4">
            <?php $form = ActiveForm::begin(['id' => 'solicitud-abastecimiento-form']); ?>
            <div class="row g-3">
                <div class="col-md-7">
                    <?= $form->field($model, 'id_material')->dropDownList($opcionesMateriales, [
                        'prompt' => 'Selecciona un material',
                        'class' => 'form-select',
                    ]) ?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'cantidad_solicitada')->input('number', [
                        'min' => '0.01',
                        'step' => '0.01',
                        'class' => 'form-control',
                    ]) ?>
                </div>
                <div class="col-12">
                    <?= $form->field($model, 'observaciones')->textarea([
                        'rows' => 3,
                        'maxlength' => 2000,
                        'placeholder' => 'Indica por qué se requiere el material o cualquier detalle de entrega.',
                    ]) ?>
                </div>
            </div>
            <div class="admin-form-actions mt-4">
                <?= Html::submitButton('Enviar solicitud', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Cancelar', ['/reportes/view', 'id_reporte' => $reporte->id_reporte], ['class' => 'btn btn-outline-secondary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    <?php endif; ?>
</section>
