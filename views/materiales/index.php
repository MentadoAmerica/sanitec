<?php

use app\models\Materiales;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\MaterialesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Materiales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="materiales-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Materiales', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_material',
            'nombre',
            'descripcion:ntext',
            'unidad_medida',
            'stock_actual',
            //'stock_minimo',
            //'costo_unitario',
            //'estado',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Materiales $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_material' => $model->id_material]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
