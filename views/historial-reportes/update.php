<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\HistorialReportes $model */

$this->title = 'Editar registro de historial #' . $model->id_historial;
$this->params['breadcrumbs'][] = ['label' => 'Historial de reportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_historial, 'url' => ['view', 'id_historial' => $model->id_historial]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="historial-reportes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
