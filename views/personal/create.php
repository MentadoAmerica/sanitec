<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Personal $model */

$this->title = 'Nuevo registro de personal';
$this->params['breadcrumbs'][] = ['label' => 'Personal de limpieza', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Nuevo registro de personal</h1>
            <p class="admin-page-subtitle">Da de alta a un trabajador y asocia su tarjeta institucional.</p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>