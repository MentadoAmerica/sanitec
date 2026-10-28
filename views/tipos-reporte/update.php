<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TiposReporte $model */

$this->title = 'Editar tipo de reporte';
$this->params['breadcrumbs'][] = ['label' => 'Tipos de reporte', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Catálogo</span>
            <h1 class="admin-page-title">Editar tipo de reporte</h1>
            <p class="admin-page-subtitle"><?= Html::encode($model->nombre) ?></p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>