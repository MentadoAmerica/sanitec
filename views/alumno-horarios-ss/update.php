<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AlumnoHorariosSs $model */

$this->title = 'Editar inscripción';
$this->params['breadcrumbs'][] = ['label' => 'Inscripción a turnos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Inscripción #' . $model->id_alumno_horario, 'url' => ['view', 'id_alumno_horario' => $model->id_alumno_horario]];
$this->params['breadcrumbs'][] = 'Editar';

$nombreAlumno = $model->alumno && $model->alumno->usuario
    ? $model->alumno->usuario->getNombreCompleto()
    : 'alumno';
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Servicio Social</span>
            <h1 class="admin-page-title">Editar inscripción</h1>
            <p class="admin-page-subtitle"><?= Html::encode($nombreAlumno) ?></p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>