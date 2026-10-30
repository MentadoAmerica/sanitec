<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AsignacionesPersonal $model */

$this->title = 'Nueva asignación de personal';
$this->params['breadcrumbs'][] = ['label' => 'Asignaciones de personal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asignaciones-personal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
