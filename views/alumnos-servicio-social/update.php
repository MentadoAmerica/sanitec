<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AlumnosServicioSocial $model */

$this->title = 'Editar alumno de servicio social';
$this->params['breadcrumbs'][] = ['label' => 'Alumnos de servicio social', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Alumno #' . $model->id_alumno, 'url' => ['view', 'id_alumno' => $model->id_alumno]];
$this->params['breadcrumbs'][] = 'Editar';

$nombre = $model->usuario ? $model->usuario->getNombreCompleto() : 'alumno';
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Servicio Social</span>
            <h1 class="admin-page-title">Editar alumno</h1>
            <p class="admin-page-subtitle"><?= Html::encode($nombre) ?></p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>