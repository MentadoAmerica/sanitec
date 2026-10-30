<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var int $reportesPendientes */
/** @var int $reportesEnAtencion */
/** @var int $reportesAtendidosHoy */
/** @var int $rondasPendientesHoy */
/** @var int $rondasCompletadasHoy */
/** @var int $reportesGenerados */
/** @var string $nombreAlumno */
/** @var array $horariosAsignados */
/** @var array $reportesActivos */
/** @var array $rondasPendientes */

$this->title = 'Mis actividades';
$this->params['breadcrumbs'][] = $this->title;

$clasePrio = function (string $p): string {
    $p = strtolower(trim($p));
    return 'admin-prioridad-' . ($p !== '' ? $p : 'normal');
};
?>

<section class="admin-dashboard">
    <div class="admin-welcome mb-4">
        <div>
            <span class="admin-eyebrow">SANITEC · SERVICIO SOCIAL</span>
            <h1 class="h2 mb-2">Hola, <?= Html::encode($nombreAlumno) ?></h1>
            <p class="mb-0">Tus horarios, rondas asignadas e incidencias reportadas.</p>
        </div>
        <div class="admin-date">
            <i class="bi bi-calendar3 me-2" aria-hidden="true"></i>
            <?= Html::encode(date('d/m/Y')) ?>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <?= Html::a('Ver mis rondas', ['/rondas-programadas/index'], ['class' => 'btn btn-servicio-social']) ?>
        <?= Html::a('Historial de verificaciones', ['/verificaciones/index'], ['class' => 'btn btn-servicio-social']) ?>
        <?= Html::a('Mis reportes', ['/reportes/index'], ['class' => 'btn btn-servicio-social']) ?>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card<?= $reportesPendientes > 0 ? ' admin-metric-alert' : '' ?>">
                <div class="admin-metric-icon"><i class="bi bi-signpost-split" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Rondas pendientes hoy</div>
                <div class="admin-metric-value"><?= number_format($rondasPendientesHoy) ?></div>
                <div class="admin-metric-note">Por iniciar o finalizar</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card">
                <div class="admin-metric-icon"><i class="bi bi-check2-circle" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Rondas completadas hoy</div>
                <div class="admin-metric-value"><?= number_format($rondasCompletadasHoy) ?></div>
                <div class="admin-metric-note">Registradas por tu horario</div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <article class="admin-metric-card">
                <div class="admin-metric-icon"><i class="bi bi-clipboard-data" aria-hidden="true"></i></div>
                <div class="admin-metric-label">Reportes generados</div>
                <div class="admin-metric-value"><?= number_format($reportesGenerados) ?></div>
                <div class="admin-metric-note">Pendientes: <?= number_format($reportesPendientes) ?> · En atención: <?= number_format($reportesEnAtencion) ?></div>
            </article>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <section class="admin-panel h-100">
                <div class="admin-panel-heading">
                    <div>
                        <span class="admin-eyebrow">SEGUIMIENTO</span>
                        <h2 class="h5 mb-0">Mis reportes recientes</h2>
                    </div>
                    <?= Html::a('Ver historial', ['/reportes/index'], ['class' => 'admin-text-link']) ?>
                </div>

                <?php if (empty($reportesActivos)): ?>
                    <div class="admin-empty-state">
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        <span>Aún no has generado reportes.</span>
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
                                    <div class="admin-muted-label small mb-1">
                                        <?= Html::encode(Yii::$app->formatter->asDatetime($r['fecha_hora_reporte'], 'php:H:i')) ?>
                                    </div>
                                    <?= Html::a('Detalle', ['/reportes/view', 'id_reporte' => $r['id_reporte']], ['class' => 'small']) ?>
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
                        <h2 class="h5 mb-0">Próximas rondas</h2>
                    </div>
                    <?= Html::a('Ver todas', ['/rondas-programadas/index'], ['class' => 'admin-text-link']) ?>
                </div>
                <?php if (empty($rondasPendientes)): ?>
                    <div class="admin-empty-state">
                        <i class="bi bi-clipboard-check" aria-hidden="true"></i>
                        <span>No tienes rondas pendientes programadas.</span>
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
                                        · <?= Html::encode(Yii::$app->formatter->asDate($rp['fecha_programada'], 'php:d/m')) ?>
                                        <?= Html::encode(substr($rp['hora_programada'], 0, 5)) ?>
                                    </div>
                                </div>
                                <?= Html::a('Abrir', ['/rondas-programadas/index'], ['class' => 'small']) ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>

    <section class="admin-panel mt-3">
        <div class="admin-panel-heading">
            <div>
                <span class="admin-eyebrow">HORARIO ASIGNADO</span>
                <h2 class="h5 mb-0">Mis turnos activos</h2>
            </div>
        </div>
        <?php if ($horariosAsignados === []): ?>
            <div class="admin-empty-state">
                <i class="bi bi-calendar-x" aria-hidden="true"></i>
                <span>No tienes un horario activo asignado. Consulta con administración.</span>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr><th>Turno</th><th>Día</th><th>Horario</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($horariosAsignados as $horario): ?>
                        <tr>
                            <td><?= Html::encode($horario['nombre_turno']) ?></td>
                            <td><?= Html::encode($horario['dia_semana']) ?></td>
                            <td><?= Html::encode(substr($horario['hora_inicio'], 0, 5)) ?>–<?= Html::encode(substr($horario['hora_fin'], 0, 5)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</section>