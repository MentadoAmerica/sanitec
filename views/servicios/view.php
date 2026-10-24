<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Servicios $model */

$this->title = $model->id_servicio;
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="servicios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_servicio' => $model->id_servicio], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_servicio' => $model->id_servicio], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_servicio',
            'id_personal',
            'id_sanitario',
            'fecha',
            'hora_checkin',
            'hora_checkout',
            'observaciones:ntext',
        ],
    ]) ?>

</div>
