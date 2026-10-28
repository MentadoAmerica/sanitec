<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Reportes $model */
/** @var app\models\HistorialReportes[] $historial */

$this->title = 'Reporte #' . $model->id_reporte;
$this->params['breadcrumbs'][] = ['label' => 'Reportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$esAdmin = (int) Yii::$app->user->identity->id_rol === 1;
$puedeEditar = $esAdmin && $model->estado !== 'Cerrado';
$siguientes = $model->getSiguientesEstados();
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Reporte #<?= Html::encode($model->id_reporte) ?></span>
            <h1 class="admin-page-title"><?= Html::encode($model->tipoReporte ? $model->tipoReporte->nombre : 'Reporte') ?></h1>
            <p class="admin-page-subtitle">
                <?= Html::encode($model->sanitario ? $model->sanitario->nombre : '') ?>
                <?php if ($model->sanitario && $model->sanitario->edificio): ?>
                    · <?= Html::encode($model->sanitario->edificio->nombre) ?>
                <?php endif; ?>
                · <?= Html::encode(Yii::$app->formatter->asDatetime($model->fecha_hora_reporte, 'php:d/m/Y H:i')) ?>
            </p>
        </div>
        <div class="admin-page-actions">
            <?php if ($puedeEditar): ?>
                <?= Html::a('Editar', ['update', 'id_reporte' => $model->id_reporte], ['class' => 'btn-secondary-admin']) ?>
            <?php endif; ?>
            <?= Html::a('Volver al listado', ['index'], ['class' => 'btn-cancel-admin']) ?>
        </div>
    </header>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="admin-card">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table admin-table admin-detail-table mb-0'],
                    'attributes' => [
                        [
                            'label' => 'Estado actual',
                            'format' => 'raw',
                            'value' => Html::tag('span', $model->estado, ['class' => 'admin-badge ' . $model->getClaseEstado()]),
                        ],
                        [
                            'attribute' => 'prioridad',
                            'label' => 'Prioridad',
                            'format' => 'raw',
                            'value' => Html::tag(
                                'span',
                                ucfirst($model->prioridad ?: 'normal'),
                                ['class' => 'admin-prioridad admin-prioridad-' . ($model->prioridad ?: 'normal')]
                            ),
                        ],
                        [
                            'label' => 'Categoría',
                            'value' => $model->tipoReporte ? ucfirst($model->tipoReporte->categoria) : '—',
                        ],
                        [
                            'label' => 'Reportado por',
                            'value' => $model->usuarioReporta ? $model->usuarioReporta->getNombreCompleto() : '—',
                        ],
                        [
                            'label' => 'Origen',
                            'value' => $model->id_verificacion
                                ? 'Verificación #' . $model->id_verificacion
                                : 'Reporte directo',
                        ],
                        [
                            'attribute' => 'descripcion',
                            'label' => 'Descripción',
                            'value' => $model->descripcion ?: '—',
                        ],
                        [
                            'attribute' => 'evidencia_foto',
                            'label' => 'Evidencia',
                            'format' => 'raw',
                            'value' => $model->evidencia_foto
                                ? Html::a('Ver evidencia', $model->evidencia_foto, ['target' => '_blank', 'rel' => 'noopener'])
                                : '—',
                        ],
                        [
                            'attribute' => 'fecha_cierre',
                            'label' => 'Fecha de cierre',
                            'format' => 'raw',
                            'value' => $model->fecha_cierre
                                ? Yii::$app->formatter->asDatetime($model->fecha_cierre, 'php:d/m/Y H:i')
                                : '<span class="admin-muted-count">Aún abierto</span>',
                        ],
                        [
                            'label' => 'Tiempo transcurrido',
                            'value' => $model->getTiempoTranscurrido(),
                        ],
                    ],
                ]); ?>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <?php if ($esAdmin && !empty($siguientes)): ?>
                <div class="admin-card mb-3">
                    <h3 class="admin-form-section-title">Cambiar estado</h3>
                    <p class="admin-form-section-hint">Registra el avance del reporte.</p>

                    <?= Html::beginForm(['cambiar-estado', 'id_reporte' => $model->id_reporte], 'post') ?>
                        <div class="mb-2">
                            <label class="form-label" for="nuevo_estado">Nuevo estado</label>
                            <select name="nuevo_estado" id="nuevo_estado" class="form-select" required>
                                <?php foreach ($siguientes as $s): ?>
                                    <option value="<?= Html::encode($s) ?>"><?= Html::encode($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="observacion">Observación (opcional)</label>
                            <textarea name="observacion" id="observacion" class="form-control" rows="3" placeholder="Nota del cambio"></textarea>
                        </div>
                        <?= Html::submitButton('Actualizar estado', ['class' => 'btn-primary-admin w-100']) ?>
                    <?= Html::endForm() ?>
                </div>
            <?php endif; ?>

            <div class="admin-card">
                <h3 class="admin-form-section-title mb-3">Historial de cambios</h3>
                <?php if (empty($historial)): ?>
                    <p class="admin-muted-count mb-0">Aún no hay cambios registrados.</p>
                <?php else: ?>
                    <ul class="admin-timeline">
                        <?php foreach ($historial as $h): ?>
                            <li class="admin-timeline-item">
                                <div class="admin-timeline-marker"></div>
                                <div class="admin-timeline-content">
                                    <div class="admin-timeline-header">
                                        <span class="admin-timeline-from"><?= Html::encode($h->estado_anterior ?? 'nuevo') ?></span>
                                        <i class="bi bi-arrow-right"></i>
                                        <span class="admin-timeline-to"><?= Html::encode($h->estado_nuevo) ?></span>
                                    </div>
                                    <div class="admin-timeline-meta">
                                        <?= Html::encode(Yii::$app->formatter->asDatetime($h->fecha_hora, 'php:d/m/Y H:i')) ?>
                                        <?php if ($h->usuario): ?>
                                            · <?= Html::encode($h->usuario->getNombreCompleto()) ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($h->observaciones): ?>
                                        <p class="admin-timeline-note"><?= Html::encode($h->observaciones) ?></p>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>