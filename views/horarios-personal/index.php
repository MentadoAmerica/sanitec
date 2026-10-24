<?php

use app\models\HorariosPersonal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\HorariosPersonalSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Horarios Personals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="horarios-personal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Horarios Personal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_horario',
            'id_personal',
            'dia_semana',
            'hora_inicio',
            'hora_fin',
            //'estado',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, HorariosPersonal $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_horario' => $model->id_horario]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
