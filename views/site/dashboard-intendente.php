<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $reportesPendientes */
/** @var array $reportesEnAtencion */
/** @var int $totalPendientes */
/** @var int $totalEnAtencion */
/** @var int $atendidosHoy */

$this->title = 'Reportes del día';
$this->params['breadcrumbs'][] = $this->title;

$clasePrio = function (string $p): string {
    $p = strtolower(trim($p));
    return 'admin-prioridad-' . ($p !== '' ? $p : 'normal');
};
?>

<section class="monitor-screen">
    <header class="monitor-header">
        <div>
            <span class="monitor-eyebrow">SANITEC · MONITOR DE SERVICIO</span>
            <h1 class="monitor-title">Reportes del día</h1>
        </div>
        <div class="monitor-clock">
            <div class="monitor-time" id="monitor-time"><?= date('H:i') ?></div>
            <?php
            $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
            $fechaEspanol = $dias[(int) date('w')] . ' ' . date('d') . ' de ' . $meses[(int) date('n') - 1] . ' de ' . date('Y');
            ?>
            <div class="monitor-date"><?= Html::encode($fechaEspanol) ?></div>
        </div>
    </header>

    <div class="monitor-summary">
        <div class="monitor-counter<?= $totalPendientes > 0 ? ' monitor-counter-danger' : '' ?>">
            <div class="monitor-counter-label">Pendientes</div>
            <div class="monitor-counter-value"><?= number_format($totalPendientes) ?></div>
        </div>
        <div class="monitor-counter<?= $totalEnAtencion > 0 ? ' monitor-counter-info' : '' ?>">
            <div class="monitor-counter-label">En atención</div>
            <div class="monitor-counter-value"><?= number_format($totalEnAtencion) ?></div>
        </div>
        <div class="monitor-counter monitor-counter-ok">
            <div class="monitor-counter-label">Atendidos hoy</div>
            <div class="monitor-counter-value"><?= number_format($atendidosHoy) ?></div>
        </div>
    </div>

    <?php if (empty($reportesPendientes) && empty($reportesEnAtencion)): ?>
        <div class="monitor-empty">
            <i class="bi bi-check2-circle" aria-hidden="true"></i>
            <div>No hay reportes pendientes en este momento.</div>
            <small>Esta pantalla se actualizará cuando llegue una nueva incidencia.</small>
        </div>
    <?php else: ?>
        <?php if (!empty($reportesPendientes)): ?>
            <h2 class="monitor-section-title">Pendientes por atender</h2>
            <div class="monitor-grid">
                <?php foreach ($reportesPendientes as $r): ?>
                    <article class="monitor-card <?= strtolower($r['prioridad_efectiva'] ?? '') === 'urgente' ? 'monitor-card-urgent' : '' ?>">
                        <div class="monitor-card-head">
                            <span class="admin-prioridad <?= $clasePrio($r['prioridad_efectiva'] ?? 'normal') ?>">
                                <?= Html::encode(ucfirst($r['prioridad_efectiva'] ?? 'normal')) ?>
                            </span>
                            <span class="monitor-card-time">
                                <?= Html::encode(Yii::$app->formatter->asDatetime($r['fecha_hora_reporte'], 'php:H:i')) ?>
                            </span>
                        </div>
                        <h3 class="monitor-card-title"><?= Html::encode($r['tipo_nombre']) ?></h3>
                        <div class="monitor-card-location">
                            <strong><?= Html::encode($r['edificio_nombre']) ?></strong>
                            · <?= Html::encode($r['sanitario_nombre']) ?>
                        </div>
                        <?php if (!empty($r['descripcion'])): ?>
                            <p class="monitor-card-desc"><?= Html::encode(mb_substr($r['descripcion'], 0, 100)) ?><?= mb_strlen($r['descripcion']) > 100 ? '…' : '' ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($reportesEnAtencion)): ?>
            <h2 class="monitor-section-title monitor-section-info">En atención</h2>
            <div class="monitor-grid">
                <?php foreach ($reportesEnAtencion as $r): ?>
                    <article class="monitor-card monitor-card-info">
                        <div class="monitor-card-head">
                            <span class="admin-prioridad <?= $clasePrio($r['prioridad_efectiva'] ?? 'normal') ?>">
                                <?= Html::encode(ucfirst($r['prioridad_efectiva'] ?? 'normal')) ?>
                            </span>
                            <span class="monitor-card-time">
                                <?= Html::encode(Yii::$app->formatter->asDatetime($r['fecha_hora_reporte'], 'php:H:i')) ?>
                            </span>
                        </div>
                        <h3 class="monitor-card-title"><?= Html::encode($r['tipo_nombre']) ?></h3>
                        <div class="monitor-card-location">
                            <strong><?= Html::encode($r['edificio_nombre']) ?></strong>
                            · <?= Html::encode($r['sanitario_nombre']) ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php
$this->registerJs(<<<JS
(function () {
    function tick() {
        var el = document.getElementById('monitor-time');
        if (!el) return;
        var now = new Date();
        var h = String(now.getHours()).padStart(2, '0');
        var m = String(now.getMinutes()).padStart(2, '0');
        el.textContent = h + ':' + m;
    }
    tick();
    setInterval(tick, 30000);

    // auto-refresh cada 60s para monitoreo en tiempo real
    setTimeout(function () {
        window.location.reload();
    }, 60000);
})();
JS
);