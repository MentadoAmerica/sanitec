<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AlumnoHorariosSs $model */

$this->title = 'Nueva inscripción';
$this->params['breadcrumbs'][] = ['label' => 'Inscripción a turnos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Servicio Social</span>
            <h1 class="admin-page-title">Nueva inscripción</h1>
            <p class="admin-page-subtitle">Inscribe a un alumno en un turno de servicio social.</p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>