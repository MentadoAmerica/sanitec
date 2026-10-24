<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Servicios $model */

$this->title = 'Update Servicios: ' . $model->id_servicio;
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_servicio, 'url' => ['view', 'id_servicio' => $model->id_servicio]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="servicios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
