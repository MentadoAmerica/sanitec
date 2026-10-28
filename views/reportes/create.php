<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Reportes $model */

$this->title = 'Nuevo reporte';
$this->params['breadcrumbs'][] = ['label' => 'Reportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Operación</span>
            <h1 class="admin-page-title">Nuevo reporte</h1>
            <p class="admin-page-subtitle">Registra una incidencia detectada en algún sanitario.</p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>