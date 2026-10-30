<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\HistorialReportes $model */

$this->title = 'Registro de historial #' . $model->id_historial;
$this->params['breadcrumbs'][] = ['label' => 'Historial Reportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="historial-reportes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_historial' => $model->id_historial], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_historial' => $model->id_historial], [
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
            'id_historial',
            'id_reporte',
            'id_usuario',
            'estado_anterior',
            'estado_nuevo',
            'fecha_hora',
            'observaciones:ntext',
        ],
    ]) ?>

</div>
