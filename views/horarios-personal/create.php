<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\HorariosPersonal $model */

$this->title = 'Create Horarios Personal';
$this->params['breadcrumbs'][] = ['label' => 'Horarios Personals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="horarios-personal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
