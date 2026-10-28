<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\HorariosPersonal $model */

$this->title = 'Editar horario';
$this->params['breadcrumbs'][] = ['label' => 'Horarios y asignaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$nombrePersonal = $model->personal && $model->personal->usuario
    ? $model->personal->usuario->getNombreCompleto()
    : 'trabajador';
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Editar horario de intendencia</h1>
            <p class="admin-page-subtitle"><?= Html::encode($nombrePersonal) ?> · <?= Html::encode($model->dia_semana) ?></p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>