<?php

use app\models\TiposReporte;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\TiposReporteSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tipos Reportes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipos-reporte-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tipos Reporte', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_tipo_reporte',
            'nombre',
            'categoria',
            'prioridad',
            'estado',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, TiposReporte $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_tipo_reporte' => $model->id_tipo_reporte]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
