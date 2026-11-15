<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PreguntasVerificacion $model */

$this->title = 'Create Preguntas Verificacion';
$this->params['breadcrumbs'][] = ['label' => 'Preguntas Verificacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preguntas-verificacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
