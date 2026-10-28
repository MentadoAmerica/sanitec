<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AlumnosServicioSocial $model */

$this->title = 'Nuevo alumno de servicio social';
$this->params['breadcrumbs'][] = ['label' => 'Alumnos de servicio social', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Servicio Social</span>
            <h1 class="admin-page-title">Nuevo alumno</h1>
            <p class="admin-page-subtitle">Vincula la cuenta del alumno y registra su matrícula.</p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>