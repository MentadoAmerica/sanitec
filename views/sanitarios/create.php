<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Sanitarios $model */

$this->title = 'Create Sanitarios';
$this->params['breadcrumbs'][] = ['label' => 'Sanitarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sanitarios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
