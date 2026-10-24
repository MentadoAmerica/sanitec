<?php

use app\models\AsignacionesPersonal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\AsignacionesPersonalSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Asignaciones Personals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asignaciones-personal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Asignaciones Personal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_asignacion',
            'id_personal',
            'id_edificio',
            'fecha_inicio',
            'fecha_fin',
            //'estado',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, AsignacionesPersonal $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_asignacion' => $model->id_asignacion]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
