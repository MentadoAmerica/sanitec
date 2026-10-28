<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Servicios $model */

$this->title = 'Editar servicio #' . $model->id_servicio;
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Servicio #' . $model->id_servicio, 'url' => ['view', 'id_servicio' => $model->id_servicio]];
$this->params['breadcrumbs'][] = 'Editar';
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Editar servicio #<?= Html::encode($model->id_servicio) ?></h1>
            <p class="admin-page-subtitle">
                <?php if ($model->personal): ?>
                    <?= Html::encode($model->personal->getNombreMostrar()) ?>
                <?php endif; ?>
                <?php if ($model->sanitario): ?>
                    · <?= Html::encode($model->sanitario->nombre) ?>
                <?php endif; ?>
                · <?= Html::encode($model->fecha) ?>
            </p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>