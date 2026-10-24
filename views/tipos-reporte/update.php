<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TiposReporte $model */

$this->title = 'Update Tipos Reporte: ' . $model->id_tipo_reporte;
$this->params['breadcrumbs'][] = ['label' => 'Tipos Reportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tipo_reporte, 'url' => ['view', 'id_tipo_reporte' => $model->id_tipo_reporte]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tipos-reporte-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
