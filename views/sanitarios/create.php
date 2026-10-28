<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Sanitarios $model */

$this->title = 'Nuevo sanitario';
$this->params['breadcrumbs'][] = ['label' => 'Sanitarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Nuevo sanitario</h1>
            <p class="admin-page-subtitle">Registra la información del nuevo sanitario.</p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>