<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\HorariosPersonal $model */

$this->title = 'Nuevo horario';
$this->params['breadcrumbs'][] = ['label' => 'Horarios y asignaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Nuevo horario de intendencia</h1>
            <p class="admin-page-subtitle">Define el horario laboral del trabajador para un día de la semana.</p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>