<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\RespuestasVerificacion $model */

$this->title = $model->id_respuesta;
$this->params['breadcrumbs'][] = ['label' => 'Respuestas Verificacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="respuestas-verificacion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_respuesta' => $model->id_respuesta], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_respuesta' => $model->id_respuesta], [
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
            'id_respuesta',
            'id_verificacion',
            'id_pregunta',
            'respuesta',
            'observacion:ntext',
        ],
    ]) ?>

</div>
