<?php

use app\models\PreguntasVerificacion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\PreguntasVerificacionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Preguntas Verificacions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preguntas-verificacion-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Preguntas Verificacion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_pregunta',
            'pregunta:ntext',
            'categoria',
            'tipo_respuesta',
            'opciones_json:ntext',
            //'respuestas_reporte_json:ntext',
            //'orden',
            //'obligatoria',
            //'estado',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PreguntasVerificacion $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_pregunta' => $model->id_pregunta]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
