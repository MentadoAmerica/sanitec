<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Edificios $model */

$this->title = 'Create Edificios';
$this->params['breadcrumbs'][] = ['label' => 'Edificios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="edificios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
