<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\models\Sanitarios $model */
$this->title = 'Código QR - ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Sanitarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Código QR del sanitario</h1>
            <p class="admin-page-subtitle"><?= Html::encode($model->edificio ? $model->edificio->nombre : 'Edificio no asignado') ?> / <?= Html::encode($model->nombre) ?></p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a('Descargar PNG', ['download-qr', 'id_sanitario' => $model->id_sanitario], ['class' => 'btn-primary-admin', 'target' => '_blank']) ?>
            <?= Html::a('Volver', ['index'], ['class' => 'btn-cancel-admin']) ?>
        </div>
    </header>

    <div class="admin-card text-center p-4">
        <div class="d-inline-block bg-white p-4 border rounded shadow-sm">
            <img src="<?= Url::to(['download-qr', 'id_sanitario' => $model->id_sanitario]) ?>" alt="Código QR para <?= Html::encode($model->nombre) ?>" style="max-width: 320px; width: 100%;" />
        </div>

        <div class="mt-4">
            <p class="mb-2"><strong>Token:</strong> <code><?= Html::encode($model->codigo_qr) ?></code></p>
            <p class="mb-0"><strong>Encuesta pública:</strong> <?= Html::a(
                'Abrir encuesta',
                ['/encuestas/qr', 'token' => $model->codigo_qr],
                ['target' => '_blank']
            ) ?></p>
        </div>
    </div>
</div>
