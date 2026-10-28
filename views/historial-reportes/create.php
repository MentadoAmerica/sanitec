<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\HistorialReportes $model */

$this->title = 'Create Historial Reportes';
$this->params['breadcrumbs'][] = ['label' => 'Historial Reportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historial-reportes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
