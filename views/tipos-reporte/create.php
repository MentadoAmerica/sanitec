<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TiposReporte $model */

$this->title = 'Create Tipos Reporte';
$this->params['breadcrumbs'][] = ['label' => 'Tipos Reportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipos-reporte-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
