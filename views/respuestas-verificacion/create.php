<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\RespuestasVerificacion $model */

$this->title = 'Create Respuestas Verificacion';
$this->params['breadcrumbs'][] = ['label' => 'Respuestas Verificacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="respuestas-verificacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
