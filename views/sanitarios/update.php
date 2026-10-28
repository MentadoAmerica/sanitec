<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Sanitarios $model */

$this->title = 'Editar sanitario';
$this->params['breadcrumbs'][] = ['label' => 'Sanitarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Editar sanitario</h1>
            <p class="admin-page-subtitle">
                <?= Html::encode($model->nombre) ?>
                <?php if ($model->edificio !== null): ?>
                    · <?= Html::encode($model->edificio->nombre) ?>
                <?php endif; ?>
            </p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</section>