<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TiposReporte $model */

$this->title = 'Nuevo tipo de reporte';
$this->params['breadcrumbs'][] = ['label' => 'Tipos de reporte', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Catálogo</span>
            <h1 class="admin-page-title">Nuevo tipo de reporte</h1>
            <p class="admin-page-subtitle">Define una nueva categoría de incidencia.</p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>