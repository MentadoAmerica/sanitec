<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Usuarios $model */

$this->title = 'Editar usuario';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Editar usuario</h1>
            <p class="admin-page-subtitle"><?= Html::encode($model->getNombreCompleto()) ?></p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>