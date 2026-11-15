<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PreguntasVerificacion $model */

$this->title = $model->id_pregunta;
$this->params['breadcrumbs'][] = ['label' => 'Preguntas Verificacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="preguntas-verificacion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_pregunta' => $model->id_pregunta], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_pregunta' => $model->id_pregunta], [
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
            'id_pregunta',
            'pregunta:ntext',
            'categoria',
            'tipo_respuesta',
            'opciones_json:ntext',
            'respuestas_reporte_json:ntext',
            'orden',
            'obligatoria',
            'estado',
        ],
    ]) ?>

</div>
