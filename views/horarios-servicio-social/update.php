<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\HorariosServicioSocial $model */

$this->title = 'Editar turno';
$this->params['breadcrumbs'][] = ['label' => 'Horarios y asignaciones', 'url' => ['horarios-personal/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Editar turno de servicio social</h1>
            <p class="admin-page-subtitle"><?= Html::encode($model->nombre_turno) ?> · <?= Html::encode($model->dia_semana) ?></p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>