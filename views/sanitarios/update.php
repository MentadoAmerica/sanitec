<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Sanitarios $model */

$this->title = 'Update Sanitarios: ' . $model->id_sanitario;
$this->params['breadcrumbs'][] = ['label' => 'Sanitarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_sanitario, 'url' => ['view', 'id_sanitario' => $model->id_sanitario]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sanitarios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
