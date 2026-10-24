<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\AsignacionesPersonal $model */

$this->title = $model->id_asignacion;
$this->params['breadcrumbs'][] = ['label' => 'Asignaciones Personals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="asignaciones-personal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_asignacion' => $model->id_asignacion], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_asignacion' => $model->id_asignacion], [
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
            'id_asignacion',
            'id_personal',
            'id_edificio',
            'fecha_inicio',
            'fecha_fin',
            'estado',
        ],
    ]) ?>

</div>
