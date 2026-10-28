<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\HistorialReportes $model */

$this->title = 'Update Historial Reportes: ' . $model->id_historial;
$this->params['breadcrumbs'][] = ['label' => 'Historial Reportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_historial, 'url' => ['view', 'id_historial' => $model->id_historial]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="historial-reportes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
