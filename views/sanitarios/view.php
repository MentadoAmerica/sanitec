<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Sanitarios $model */

$this->title = $model->id_sanitario;
$this->params['breadcrumbs'][] = ['label' => 'Sanitarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sanitarios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_sanitario' => $model->id_sanitario], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_sanitario' => $model->id_sanitario], [
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
            'id_sanitario',
            'id_edificio',
            'nombre',
            'tipo',
            'ubicacion',
            'codigo_qr',
            'estado',
        ],
    ]) ?>

</div>
