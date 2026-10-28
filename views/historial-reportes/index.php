<?php

use app\models\HistorialReportes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\HistorialReportesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Historial Reportes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historial-reportes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Historial Reportes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_historial',
            'id_reporte',
            'id_usuario',
            'estado_anterior',
            'estado_nuevo',
            //'fecha_hora',
            //'observaciones:ntext',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, HistorialReportes $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_historial' => $model->id_historial]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
