<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Servicios $model */

$this->title = 'Servicio #' . $model->id_servicio;
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Servicio #<?= Html::encode($model->id_servicio) ?></h1>
            <p class="admin-page-subtitle">
                <?php if ($model->sanitario): ?>
                    <?= Html::encode($model->sanitario->nombre) ?>
                    <?php if ($model->sanitario->edificio): ?>
                        · <?= Html::encode($model->sanitario->edificio->nombre) ?>
                    <?php endif; ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a('Editar', ['update', 'id_servicio' => $model->id_servicio], ['class' => 'btn-primary-admin']) ?>
            <?= Html::a('Volver al listado', ['index'], ['class' => 'btn-cancel-admin']) ?>
        </div>
    </header>

    <div class="admin-card">
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table admin-table admin-detail-table mb-0'],
            'attributes' => [
                [
                    'attribute' => 'id_servicio',
                    'label' => 'ID',
                ],
                [
                    'label' => 'Trabajador',
                    'value' => $model->personal ? $model->personal->getNombreMostrar() : '—',
                ],
                [
                    'label' => 'Identificador de tarjeta',
                    'value' => $model->personal ? $model->personal->identificador : '—',
                ],
                [
                    'label' => 'Sanitario',
                    'value' => $model->sanitario ? $model->sanitario->nombre : '—',
                ],
                [
                    'label' => 'Edificio',
                    'value' => $model->sanitario && $model->sanitario->edificio
                        ? $model->sanitario->edificio->nombre
                        : '—',
                ],
                [
                    'attribute' => 'fecha',
                    'label' => 'Fecha',
                    'format' => ['date', 'php:d/m/Y'],
                ],
                [
                    'attribute' => 'hora_checkin',
                    'label' => 'Entrada',
                    'format' => ['datetime', 'php:d/m/Y H:i'],
                ],
                [
                    'attribute' => 'hora_checkout',
                    'label' => 'Salida',
                    'format' => 'raw',
                    'value' => function ($m) {
                        if ($m->hora_checkout === null) {
                            return '<span class="admin-badge admin-badge-warning">En curso</span>';
                        }
                        return Yii::$app->formatter->asDatetime($m->hora_checkout, 'php:d/m/Y H:i');
                    },
                ],
                [
                    'label' => 'Duración',
                    'value' => $model->getDuracionFormateada(),
                ],
                [
                    'attribute' => 'observaciones',
                    'label' => 'Observaciones',
                    'value' => $model->observaciones ?: '—',
                ],
            ],
        ]); ?>
    </div>
</section>