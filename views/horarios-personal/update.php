<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\HorariosPersonal $model */

$this->title = 'Update Horarios Personal: ' . $model->id_horario;
$this->params['breadcrumbs'][] = ['label' => 'Horarios Personals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_horario, 'url' => ['view', 'id_horario' => $model->id_horario]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="horarios-personal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
