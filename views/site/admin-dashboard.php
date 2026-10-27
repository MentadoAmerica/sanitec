<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Panel de administración';
$this->params['breadcrumbs'][] = $this->title;
$usuario = Yii::$app->user->identity;
$nombreAdministrador = trim(($usuario->nombre ?? '') . ' ' . ($usuario->apellidos ?? ''));
$formatearExistencia = static function ($cantidad): string {
    return rtrim(rtrim(number_format((float) $cantidad, 2, '.', ','), '0'), '.');
};
$formatearDuracion = static function (string $inicio, ?string $fin): string {
    if ($fin === null) {
        return 'En curso';
    }

    $minutos = max(0, (int) floor((strtotime($fin) - strtotime($inicio)) / 60));
    return $minutos >= 60
        ? intdiv($minutos, 60) . ' h ' . ($minutos % 60) . ' min'
        : $minutos . ' min';
};
$claseEstadoReporte = static function (string $estado): string {
    $estado = strtr(mb_strtolower(trim($estado), 'UTF-8'), [
        'á' => 'a',
        'é' => 'e',
        'í' => 'i',
        'ó' => 'o',
        'ú' => 'u',
    ]);

    if (in_array($estado, ['cerrado', 'cerrada', 'closed'], true)) {
        return 'admin-status-closed';
    }
    if (in_array($estado, ['en proceso', 'en_proceso', 'proceso', 'in progress'], true)) {
        return 'admin-status-progress';
    }

    return 'admin-status-open';
};
?>

<section class="admin-dashboard">
    <div class="admin-welcome mb-4">
        <div>
            <span class="admin-eyebrow">SANITEC · ADMINISTRACIÓN</span>
            <h1 class="h2 mb-2">Bienvenido<?= $nombreAdministrador !== '' ? ', ' . Html::encode($nombreAdministrador) : '' ?></h1>
            <p class="mb-0">Consulta el estado general y da seguimiento a las actividades del sistema.</p>
        </div>
        <div class="admin-date">
            <i class="bi bi-calendar3 me-2" aria-hidden="true"></i>
            <?= Html::encode(date('d/m/Y')) ?>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card">
                <div class="admin-metric-icon"><i class="bi bi-door-open" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Sanitarios registrados</div>
                <div class="admin-metric-value"><?= number_format($sanitarios['total']) ?></div>
                <div class="admin-metric-note">
                    <span><?= number_format($sanitarios['activos']) ?> activos</span>
                    <span class="admin-note-separator">·</span>
                    <span><?= number_format($sanitarios['inactivos']) ?> inactivos</span>
                </div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card">
                <div class="admin-metric-icon"><i class="bi bi-clipboard-check" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Servicios registrados</div>
                <div class="admin-metric-value"><?= number_format($totalServicios) ?></div>
                <div class="admin-metric-note">Registros de atención</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card">
                <div class="admin-metric-icon"><i class="bi bi-search" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Verificaciones realizadas</div>
                <div class="admin-metric-value"><?= number_format($totalVerificaciones) ?></div>
                <div class="admin-metric-note">Rondas finalizadas</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card">
                <div class="admin-metric-icon"><i class="bi bi-emoji-smile" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Satisfacción de usuarios</div>
                <div class="admin-metric-value">
                    <?= $satisfaccion !== null ? number_format((float) $satisfaccion, 1) : '—' ?>
                    <span class="admin-metric-suffix">/ 5</span>
                </div>
                <div class="admin-metric-note">Promedio de evaluaciones</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card admin-metric-alert">
                <div class="admin-metric-icon"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Materiales con stock bajo</div>
                <div class="admin-metric-value"><?= number_format($totalMaterialesBajos) ?></div>
                <div class="admin-metric-note">En o por debajo del mínimo</div>
            </article>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12">
            <section class="admin-panel h-100">
                <div class="admin-panel-heading">
                    <div>
                        <span class="admin-eyebrow">SEGUIMIENTO</span>
                        <h2 class="h5 mb-0">Reportes e incidencias</h2>
                    </div>
                </div>
                <div class="row g-3">
                    <?php foreach ([
                        ['label' => 'Abiertos', 'count' => $reportCounts['abierto'], 'class' => 'admin-status-open'],
                        ['label' => 'En proceso', 'count' => $reportCounts['en proceso'], 'class' => 'admin-status-progress'],
                        ['label' => 'Cerrados', 'count' => $reportCounts['cerrado'], 'class' => 'admin-status-closed'],
                    ] as $estado): ?>
                        <div class="col-4">
                            <div class="admin-report-counter">
                                <span class="admin-status-dot <?= $estado['class'] ?>"></span>
                                <span class="admin-report-count"><?= number_format($estado['count']) ?></span>
                                <span class="admin-report-label"><?= Html::encode($estado['label']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="admin-supply-callout mt-3">
                    <div class="admin-supply-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></div>
                    <div class="flex-grow-1">
                        <strong>Pendientes de abastecimiento</strong>
                        <div class="small text-body-secondary">Reportes de insumos que requieren atención</div>
                    </div>
                    <span class="admin-supply-count"><?= number_format($pendientesAbastecimiento) ?></span>
                </div>
            </section>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-7">
            <section class="admin-panel h-100">
                <div class="admin-panel-heading">
                    <div>
                        <span class="admin-eyebrow">INVENTARIO</span>
                        <h2 class="h5 mb-0">Materiales por reabastecer</h2>
                    </div>
                    <?= Html::a('Ir al inventario', ['/materiales/index'], ['class' => 'admin-text-link']) ?>
                </div>
                <?php if ($materialesBajos === []): ?>
                    <div class="admin-empty-state">
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        <span>No hay materiales por debajo de su stock mínimo.</span>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table admin-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Material</th>
                                    <th scope="col">Existencia</th>
                                    <th scope="col">Mínimo</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materialesBajos as $material): ?>
                                    <tr>
                                        <td class="fw-semibold"><?= Html::encode($material['nombre']) ?></td>
                                        <td>
                                            <?= Html::encode($formatearExistencia($material['stock_actual'])) ?>
                                            <?= Html::encode($material['unidad_medida'] ?? '') ?>
                                        </td>
                                        <td>
                                            <?= Html::encode($formatearExistencia($material['stock_minimo'])) ?>
                                            <?= Html::encode($material['unidad_medida'] ?? '') ?>
                                        </td>
                                        <td><span class="admin-stock-badge">Stock bajo</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </div>
        <div class="col-12 col-xl-5">
            <section class="admin-panel h-100">
                <div class="admin-panel-heading">
                    <div>
                        <span class="admin-eyebrow">INCIDENCIAS</span>
                        <h2 class="h5 mb-0">Reportes recientes</h2>
                    </div>
                    <span class="admin-muted-label">Últimos 5</span>
                </div>
                <?php if ($reportesRecientes === []): ?>
                    <div class="admin-empty-state">
                        <i class="bi bi-inbox" aria-hidden="true"></i>
                        <span>Aún no hay reportes registrados.</span>
                    </div>
                <?php else: ?>
                    <div class="admin-activity-list">
                        <?php foreach ($reportesRecientes as $reporte): ?>
                            <article class="admin-activity-item">
                                <span class="admin-activity-icon"><i class="bi bi-exclamation-circle" aria-hidden="true"></i></span>
                                <div class="min-width-0 flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <strong><?= Html::encode($reporte['tipo']) ?></strong>
                                        <span class="admin-status-badge <?= $claseEstadoReporte($reporte['estado']) ?>">
                                            <?= Html::encode($reporte['estado']) ?>
                                        </span>
                                        <?php if ($reporte['prioridad'] !== null && $reporte['prioridad'] !== ''): ?>
                                            <span class="admin-priority-badge">
                                                Prioridad: <?= Html::encode($reporte['prioridad']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="small text-body-secondary">
                                        <?= Html::encode($reporte['edificio']) ?> · <?= Html::encode($reporte['sanitario']) ?>
                                    </div>
                                    <div class="small admin-muted-label">
                                        <?= Html::encode(Yii::$app->formatter->asDatetime($reporte['fecha_hora_reporte'], 'php:d/m/Y H:i')) ?>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
        <div class="col-12">
            <section class="admin-panel">
                <div class="admin-panel-heading">
                    <div>
                        <span class="admin-eyebrow">ACTIVIDAD OPERATIVA</span>
                        <h2 class="h5 mb-0">Servicios recientes</h2>
                    </div>
                    <?= Html::a('Ver seguimiento', ['/servicios/index'], ['class' => 'admin-text-link']) ?>
                </div>
                <?php if ($serviciosRecientes === []): ?>
                    <div class="admin-empty-state">
                        <i class="bi bi-clock-history" aria-hidden="true"></i>
                        <span>Aún no hay servicios registrados.</span>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table admin-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Trabajador</th>
                                    <th scope="col">Sanitario</th>
                                    <th scope="col">Edificio</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Check-in</th>
                                    <th scope="col">Check-out</th>
                                    <th scope="col">Tiempo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($serviciosRecientes as $servicio): ?>
                                    <tr>
                                        <td class="fw-semibold"><?= Html::encode($servicio['trabajador'] ?? 'Personal') ?></td>
                                        <td><?= Html::encode($servicio['sanitario']) ?></td>
                                        <td><?= Html::encode($servicio['edificio']) ?></td>
                                        <td><?= Html::encode(Yii::$app->formatter->asDate($servicio['fecha'], 'php:d/m/Y')) ?></td>
                                        <td><?= Html::encode(Yii::$app->formatter->asTime($servicio['hora_checkin'], 'php:H:i')) ?></td>
                                        <td>
                                            <?= $servicio['hora_checkout'] !== null
                                                ? Html::encode(Yii::$app->formatter->asTime($servicio['hora_checkout'], 'php:H:i'))
                                                : '<span class="admin-muted-label">En curso</span>' ?>
                                        </td>
                                        <td><?= Html::encode($formatearDuracion($servicio['hora_checkin'], $servicio['hora_checkout'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</section>
