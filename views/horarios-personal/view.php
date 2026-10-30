<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\HorariosPersonal $model */

$this->title = 'Horario de personal #' . $model->id_horario;
$this->params['breadcrumbs'][] = ['label' => 'Horarios Personals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="horarios-personal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_horario' => $model->id_horario], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_horario' => $model->id_horario], [
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
            'id_horario',
            'id_personal',
            'dia_semana',
            'hora_inicio',
            'hora_fin',
            'estado',
        ],
    ]) ?>

</div>
