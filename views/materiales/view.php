<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Materiales $model */

$this->title = $model->id_material;
$this->params['breadcrumbs'][] = ['label' => 'Materiales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="materiales-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_material' => $model->id_material], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_material' => $model->id_material], [
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
            'id_material',
            'nombre',
            'descripcion:ntext',
            'unidad_medida',
            'stock_actual',
            'stock_minimo',
            'costo_unitario',
            'estado',
        ],
    ]) ?>

</div>
