<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PreguntasVerificacion $model */

$this->title = 'Update Preguntas Verificacion: ' . $model->id_pregunta;
$this->params['breadcrumbs'][] = ['label' => 'Preguntas Verificacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_pregunta, 'url' => ['view', 'id_pregunta' => $model->id_pregunta]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="preguntas-verificacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
