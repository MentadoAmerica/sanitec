<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Edificios $model */

$this->title = $model->id_edificio;
$this->params['breadcrumbs'][] = ['label' => 'Edificios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="edificios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_edificio' => $model->id_edificio], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_edificio' => $model->id_edificio], [
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
            'id_edificio',
            'nombre',
            'descripcion:ntext',
            'ubicacion',
            'estado',
        ],
    ]) ?>

</div>
