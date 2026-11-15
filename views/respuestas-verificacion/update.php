<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\RespuestasVerificacion $model */

$this->title = 'Update Respuestas Verificacion: ' . $model->id_respuesta;
$this->params['breadcrumbs'][] = ['label' => 'Respuestas Verificacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_respuesta, 'url' => ['view', 'id_respuesta' => $model->id_respuesta]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="respuestas-verificacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
