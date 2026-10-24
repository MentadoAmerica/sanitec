<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Servicios $model */

$this->title = 'Create Servicios';
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
