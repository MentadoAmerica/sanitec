<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\HorariosServicioSocial $model */

$this->title = 'Turno de servicio social #' . $model->id_horario_ss;
$this->params['breadcrumbs'][] = ['label' => 'Horarios Servicio Socials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="horarios-servicio-social-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_horario_ss' => $model->id_horario_ss], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_horario_ss' => $model->id_horario_ss], [
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
            'id_horario_ss',
            'nombre_turno',
            'dia_semana',
            'hora_inicio',
            'hora_fin',
            'estado',
        ],
    ]) ?>

</div>
