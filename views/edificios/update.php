<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Edificios $model */

$this->title = 'Update Edificios: ' . $model->id_edificio;
$this->params['breadcrumbs'][] = ['label' => 'Edificios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_edificio, 'url' => ['view', 'id_edificio' => $model->id_edificio]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="edificios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
