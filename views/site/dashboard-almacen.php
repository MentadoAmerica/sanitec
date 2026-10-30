<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var string $turno */
/** @var array $sinStock */
/** @var array $bajoStock */
/** @var int $totalAlertas */
/** @var array $entregasHoy */
/** @var int $totalEntregasHoy */
/** @var int $edificiosAtendidosHoy */
/** @var float $unidadesEntregadasHoy */

$this->title = 'Almacén · ' . $turno;
$this->params['breadcrumbs'][] = $this->title;

$formatear = function ($v) {
    return rtrim(rtrim(number_format((float) $v, 2, '.', ','), '0'), '.');
};
?>

<section class="admin-dashboard">
    <div class="admin-welcome mb-4">
        <div>
            <span class="admin-eyebrow">SANITEC · ALMACÉN</span>
            <h1 class="h2 mb-2">Turno <?= Html::encode($turno) ?></h1>
            <p class="mb-0">Alertas de restock y entregas del turno.</p>
        </div>
        <div class="admin-date">
            <i class="bi bi-calendar3 me-2" aria-hidden="true"></i>
            <?= Html::encode(date('d/m/Y')) ?>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card<?= $totalAlertas > 0 ? ' admin-metric-alert' : '' ?>">
                <div class="admin-metric-icon"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Materiales por restock</div>
                <div class="admin-metric-value"><?= number_format($totalAlertas) ?></div>
                <div class="admin-metric-note">Requieren aviso al admin</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card">
                <div class="admin-metric-icon"><i class="bi bi-box-arrow-up" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Entregas hoy</div>
                <div class="admin-metric-value"><?= number_format($totalEntregasHoy) ?></div>
                <div class="admin-metric-note">Materiales entregados</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card">
                <div class="admin-metric-icon"><i class="bi bi-building" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Edificios atendidos</div>
                <div class="admin-metric-value"><?= number_format($edificiosAtendidosHoy) ?></div>
                <div class="admin-metric-note">Con entrega registrada</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card">
                <div class="admin-metric-icon"><i class="bi bi-boxes" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Unidades entregadas</div>
                <div class="admin-metric-value"><?= Html::encode($formatear($unidadesEntregadasHoy)) ?></div>
                <div class="admin-metric-note">Suma del turno</div>
            </article>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-6">
            <section class="admin-panel h-100">
                <div class="admin-panel-heading">
                    <div>
                        <span class="admin-eyebrow">ALERTAS DE RESTOCK</span>
                        <h2 class="h5 mb-0">Materiales a reportar</h2>
                    </div>
                </div>
                <?php if ($totalAlertas === 0): ?>
                    <div class="admin-empty-state">
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        <span>No hay materiales bajo stock. Todo está en orden.</span>
                    </div>
                <?php else: ?>
                    <div class="admin-restock-list">
                        <?php foreach ($sinStock as $m): ?>
                            <article class="admin-restock-item admin-restock-danger">
                                <div>
                                    <strong><?= Html::encode($m['nombre']) ?></strong>
                                    <div class="admin-muted-count small">Sin existencia</div>
                                </div>
                                <span class="admin-badge admin-badge-danger">0 <?= Html::encode($m['unidad_medida']) ?></span>
                            </article>
                        <?php endforeach; ?>
                        <?php foreach ($bajoStock as $m): ?>
                            <article class="admin-restock-item">
                                <div>
                                    <strong><?= Html::encode($m['nombre']) ?></strong>
                                    <div class="admin-muted-count small">
                                        Mínimo requerido: <?= Html::encode($formatear($m['stock_minimo'])) ?>
                                    </div>
                                </div>
                                <span class="admin-badge admin-badge-warning">
                                    <?= Html::encode($formatear($m['stock_actual'])) ?> <?= Html::encode($m['unidad_medida']) ?>
                                </span>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>

        <div class="col-12 col-xl-6">
            <section class="admin-panel h-100">
                <div class="admin-panel-heading">
                    <div>
                        <span class="admin-eyebrow">HOY</span>
                        <h2 class="h5 mb-0">Entregas del turno</h2>
                    </div>
                </div>
                <?php if (empty($entregasHoy)): ?>
                    <div class="admin-empty-state">
                        <i class="bi bi-inbox" aria-hidden="true"></i>
                        <span>No se han registrado entregas durante este turno.</span>
                    </div>
                <?php else: ?>
                    <div class="admin-activity-list">
                        <?php foreach ($entregasHoy as $e): ?>
                            <article class="admin-activity-item">
                                <span class="admin-activity-icon"><i class="bi bi-box-arrow-up" aria-hidden="true"></i></span>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <strong><?= Html::encode($e['material_nombre']) ?></strong>
                                        <span class="admin-badge admin-badge-info">
                                            <?= Html::encode($formatear($e['cantidad'])) ?> <?= Html::encode($e['unidad_medida']) ?>
                                        </span>
                                    </div>
                                    <div class="small text-body-secondary">
                                        <?= Html::encode($e['edificio_nombre'] ?: 'Sin destino registrado') ?>
                                    </div>
                                    <div class="small admin-muted-label">
                                        <?= Html::encode(Yii::$app->formatter->asDatetime($e['fecha_hora'], 'php:H:i')) ?>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</section>