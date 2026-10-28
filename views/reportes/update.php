<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Reportes $model */

$this->title = 'Editar reporte #' . $model->id_reporte;
$this->params['breadcrumbs'][] = ['label' => 'Reportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Reporte #' . $model->id_reporte, 'url' => ['view', 'id_reporte' => $model->id_reporte]];
$this->params['breadcrumbs'][] = 'Editar';
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Operación</span>
            <h1 class="admin-page-title">Editar reporte #<?= Html::encode($model->id_reporte) ?></h1>
            <p class="admin-page-subtitle"><?= Html::encode($model->estado) ?></p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>