<?php

use app\models\Sanitarios;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\SanitariosSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Sanitarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sanitarios-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Sanitarios', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_sanitario',
            'id_edificio',
            'nombre',
            'tipo',
            'ubicacion',
            //'codigo_qr',
            //'estado',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Sanitarios $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_sanitario' => $model->id_sanitario]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
