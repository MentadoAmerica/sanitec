<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Edificios $model */

$this->title = 'Editar edificio';
$this->params['breadcrumbs'][] = ['label' => 'Edificios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Editar edificio</h1>
            <p class="admin-page-subtitle"><?= Html::encode($model->nombre) ?></p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>