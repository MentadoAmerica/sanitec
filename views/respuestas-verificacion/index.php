<?php

use app\models\RespuestasVerificacion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\RespuestasVerificacionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Respuestas Verificacions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="respuestas-verificacion-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Respuestas Verificacion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_respuesta',
            'id_verificacion',
            'id_pregunta',
            'respuesta',
            'observacion:ntext',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, RespuestasVerificacion $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_respuesta' => $model->id_respuesta]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
