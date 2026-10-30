<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var int $reportesPendientes */
/** @var int $reportesEnAtencion */
/** @var int $reportesAtendidosHoy */
/** @var int $rondasPendientesHoy */
/** @var int $rondasCompletadasHoy */
/** @var array $reportesActivos */
/** @var array $rondasPendientes */

$this->title = 'Seguimiento de incidencias';
$this->params['breadcrumbs'][] = $this->title;

$clasePrio = function (string $p): string {
    $p = strtolower(trim($p));
    return 'admin-prioridad-' . ($p !== '' ? $p : 'normal');
};
?>

<section class="admin-dashboard">
    <div class="admin-welcome mb-4">
        <div>
            <span class="admin-eyebrow">SANITEC · SEGUIMIENTO</span>
            <h1 class="h2 mb-2">Panel de seguimiento</h1>
            <p class="mb-0">Estado en tiempo real de los reportes y las rondas del día.</p>
        </div>
        <div class="admin-date">
            <i class="bi bi-calendar3 me-2" aria-hidden="true"></i>
            <?= Html::encode(date('d/m/Y')) ?>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card<?= $reportesPendientes > 0 ? ' admin-metric-alert' : '' ?>">
                <div class="admin-metric-icon"><i class="bi bi-exclamation-circle" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Reportes pendientes</div>
                <div class="admin-metric-value"><?= number_format($reportesPendientes) ?></div>
                <div class="admin-metric-note">Sin atender</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card">
                <div class="admin-metric-icon"><i class="bi bi-wrench-adjustable" aria-hidden="true"></i></div>
                <div class="admin-metric-label">En atención</div>
                <div class="admin-metric-value"><?= number_format($reportesEnAtencion) ?></div>
                <div class="admin-metric-note">En proceso</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card">
                <div class="admin-metric-icon"><i class="bi bi-check2-circle" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Atendidos hoy</div>
                <div class="admin-metric-value"><?= number_format($reportesAtendidosHoy) ?></div>
                <div class="admin-metric-note">Cerrados durante el día</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card">
                <div class="admin-metric-icon"><i class="bi bi-signpost-split" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Rondas hoy</div>
                <div class="admin-metric-value">
                    <?= number_format($rondasCompletadasHoy) ?>
                    <span class="admin-metric-suffix">/ <?= number_format($rondasCompletadasHoy + $rondasPendientesHoy) ?></span>
                </div>
                <div class="admin-metric-note"><?= number_format($rondasPendientesHoy) ?> pendientes</div>
            </article>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <section class="admin-panel h-100">
                <div class="admin-panel-heading">
                    <div>
                        <span class="admin-eyebrow">EN VIVO</span>
                        <h2 class="h5 mb-0">Incidencias activas</h2>
                    </div>
                    <?= Html::a('Ver todos los reportes', ['/reportes/index'], ['class' => 'admin-text-link']) ?>
                </div>

                <?php if (empty($reportesActivos)): ?>
                    <div class="admin-empty-state">
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        <span>No hay incidencias activas en este momento.</span>
                    </div>
                <?php else: ?>
                    <div class="admin-incident-list">
                        <?php foreach ($reportesActivos as $r): ?>
                            <article class="admin-incident-row">
                                <span class="admin-prioridad <?= $clasePrio($r['prioridad_efectiva'] ?? 'normal') ?>">
                                    <?= Html::encode(ucfirst($r['prioridad_efectiva'] ?? 'normal')) ?>
                                </span>
                                <div class="admin-incident-body">
                                    <strong><?= Html::encode($r['tipo_nombre']) ?></strong>
                                    <div class="admin-incident-meta">
                                        <?= Html::encode($r['edificio_nombre']) ?>
                                        · <?= Html::encode($r['sanitario_nombre']) ?>
                                    </div>
                                </div>
                                <div class="admin-incident-side">
                                    <span class="admin-badge <?= strtolower($r['estado']) === 'pendiente' ? 'admin-badge-warning' : 'admin-badge-info' ?>">
                                        <?= Html::encode($r['estado']) ?>
                                    </span>
                                    <div class="admin-muted-label small">
                                        <?= Html::encode(Yii::$app->formatter->asDatetime($r['fecha_hora_reporte'], 'php:H:i')) ?>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>

        <div class="col-12 col-xl-4">
            <section class="admin-panel h-100">
                <div class="admin-panel-heading">
                    <div>
                        <span class="admin-eyebrow">HOY</span>
                        <h2 class="h5 mb-0">Rondas pendientes</h2>
                    </div>
                </div>
                <?php if (empty($rondasPendientes)): ?>
                    <div class="admin-empty-state">
                        <i class="bi bi-clipboard-check" aria-hidden="true"></i>
                        <span>No hay rondas pendientes para hoy.</span>
                    </div>
                <?php else: ?>
                    <div class="admin-activity-list">
                        <?php foreach ($rondasPendientes as $rp): ?>
                            <article class="admin-activity-item">
                                <span class="admin-activity-icon"><i class="bi bi-geo-alt" aria-hidden="true"></i></span>
                                <div class="flex-grow-1">
                                    <strong><?= Html::encode($rp['sanitario_nombre']) ?></strong>
                                    <div class="small text-body-secondary">
                                        <?= Html::encode($rp['edificio_nombre']) ?>
                                        · <?= Html::encode(substr($rp['hora_programada'], 0, 5)) ?>
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