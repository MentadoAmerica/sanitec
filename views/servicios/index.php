<?php

use app\models\Servicios;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\ServiciosSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Servicios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicios-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Servicios', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_servicio',
            'id_personal',
            'id_sanitario',
            'fecha',
            'hora_checkin',
            //'hora_checkout',
            //'observaciones:ntext',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Servicios $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_servicio' => $model->id_servicio]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
