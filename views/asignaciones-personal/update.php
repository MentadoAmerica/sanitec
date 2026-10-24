<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AsignacionesPersonal $model */

$this->title = 'Update Asignaciones Personal: ' . $model->id_asignacion;
$this->params['breadcrumbs'][] = ['label' => 'Asignaciones Personals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_asignacion, 'url' => ['view', 'id_asignacion' => $model->id_asignacion]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="asignaciones-personal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
