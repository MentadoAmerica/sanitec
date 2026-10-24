<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Materiales $model */

$this->title = 'Update Materiales: ' . $model->id_material;
$this->params['breadcrumbs'][] = ['label' => 'Materiales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_material, 'url' => ['view', 'id_material' => $model->id_material]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="materiales-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
