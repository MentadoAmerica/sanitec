<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $reportes */
/** @var int $totalActivos */

$this->title = 'Monitor de reportes';

$ahora = time();

$clasePrio = function (string $p): string {
    $p = strtolower(trim($p));
    if ($p === 'urgente') return 'mon-row-urgente';
    if ($p === 'alta')    return 'mon-row-alta';
    if ($p === 'baja')    return 'mon-row-baja';
    return 'mon-row-normal';
};

$chipPrio = function (string $p): string {
    $p = strtolower(trim($p));
    if ($p === 'urgente') return 'mon-chip-urgente';
    if ($p === 'alta')    return 'mon-chip-alta';
    if ($p === 'baja')    return 'mon-chip-baja';
    return 'mon-chip-normal';
};

$tiempoTranscurrido = function (string $fecha) use ($ahora): string {
    $min = max(0, (int) floor(($ahora - strtotime($fecha)) / 60));
    if ($min < 60) return $min . ' min';
    $h = intdiv($min, 60);
    $m = $min % 60;
    return $h . 'h ' . $m . 'min';
};

// limpia el prefijo "DETECTADO EN RONDA:" del inicio de la descripción
$limpiarDescripcion = function (?string $desc): string {
    if (empty($desc)) {
        return '';
    }
    $desc = preg_replace('/^DETECTADO EN RONDA:\s*/i', '', $desc);
    return trim((string) $desc);
};
?>

<div class="monitor-screen-full" id="monitor-root">
    <header class="monitor-full-header">
        <div class="monitor-full-brand">
            <i class="bi bi-broadcast"></i>
            <span>SANITEC · MONITOR DE SERVICIO</span>
        </div>

        <div class="monitor-full-summary">
            <div class="monitor-full-counter<?= $totalActivos > 0 ? ' has-items' : '' ?>">
                <span class="monitor-full-counter-num"><?= number_format($totalActivos) ?></span>
                <span class="monitor-full-counter-label">reportes activos</span>
            </div>
            <div class="monitor-full-clock">
                <span id="monitor-full-time"><?= date('H:i') ?></span>
                <small id="monitor-full-date"><?= Html::encode(Yii::$app->formatter->asDate('now', 'php:l d/m/Y')) ?></small>
            </div>
            <button type="button" id="btn-fullscreen" class="monitor-fullscreen-btn" title="Pantalla completa">
                <i class="bi bi-fullscreen"></i>
            </button>
        </div>
    </header>

    <?php if (empty($reportes)): ?>
        <div class="monitor-full-empty">
            <i class="bi bi-check2-circle"></i>
            <h2>Sin reportes pendientes</h2>
            <p>Todos los sanitarios están al día.</p>
        </div>
    <?php else: ?>
        <div class="monitor-table-wrapper">
            <table class="monitor-table">
                <thead>
                    <tr>
                        <th class="col-prio">Prioridad</th>
                        <th class="col-tipo">Reporte</th>
                        <th class="col-edif">Edificio</th>
                        <th class="col-ubic">Ubicación</th>
                        <th class="col-desc">Descripción</th>
                        <th class="col-tiempo">Tiempo</th>
                        <th class="col-estado">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reportes as $r): ?>
                        <?php
                        $prioridad = $r['prioridad_reporte'] ?: $r['prioridad_tipo'] ?: 'normal';
                        $claseFila = $clasePrio($prioridad);
                        $claseChip = $chipPrio($prioridad);
                        $tiempo = $tiempoTranscurrido($r['fecha_hora_reporte']);
                        $horaReporte = date('H:i', strtotime($r['fecha_hora_reporte']));
                        $esViejo = ($ahora - strtotime($r['fecha_hora_reporte'])) > 3600;
                        $descripcion = $limpiarDescripcion($r['descripcion']);
                        ?>
                        <tr class="monitor-row <?= $claseFila ?>">
                            <td class="col-prio">
                                <span class="mon-chip <?= $claseChip ?>">
                                    <?= Html::encode(ucfirst($prioridad)) ?>
                                </span>
                            </td>
                            <td class="col-tipo">
                                <strong><?= Html::encode($r['tipo_nombre']) ?></strong>
                                <div class="mon-categoria"><?= Html::encode(ucfirst($r['categoria'])) ?></div>
                            </td>
                            <td class="col-edif">
                                <strong class="mon-edificio"><?= Html::encode($r['edificio_nombre']) ?></strong>
                            </td>
                            <td class="col-ubic">
                                <div class="mon-sanitario"><?= Html::encode($r['sanitario_nombre']) ?></div>
                                <div class="mon-sanitario-tipo"><?= Html::encode($r['sanitario_tipo'] ?: '—') ?></div>
                            </td>
                            <td class="col-desc">
                                <?php if (!empty($descripcion)): ?>
                                    <span class="mon-desc">
                                        <?= Html::encode(mb_substr($descripcion, 0, 160)) ?><?= mb_strlen($descripcion) > 160 ? '…' : '' ?>
                                    </span>
                                <?php else: ?>
                                    <span class="mon-desc mon-desc-empty">sin descripción</span>
                                <?php endif; ?>
                            </td>
                            <td class="col-tiempo">
                                <span class="mon-hora"><?= Html::encode($horaReporte) ?></span>
                                <span class="mon-tiempo <?= $esViejo ? 'is-old' : '' ?>">
                                    hace <?= Html::encode($tiempo) ?>
                                </span>
                            </td>
                            <td class="col-estado">
                                <span class="mon-estado"><?= Html::encode($r['estado']) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <footer class="monitor-full-footer">
        <span>Actualización automática cada 30 segundos</span>
        <a href="<?= Html::encode(Yii::$app->urlManager->createUrl(['/site/index'])) ?>">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </footer>
</div>

<?php
$this->registerJs(<<<JS
(function () {
    function tick() {
        var el = document.getElementById('monitor-full-time');
        if (!el) return;
        var now = new Date();
        el.textContent = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
    }
    tick();
    setInterval(tick, 30000);

    var btn = document.getElementById('btn-fullscreen');
    var root = document.getElementById('monitor-root');
    var icon = btn.querySelector('i');

    function actualizarIcono() {
        var enFull = !!document.fullscreenElement;
        icon.classList.toggle('bi-fullscreen', !enFull);
        icon.classList.toggle('bi-fullscreen-exit', enFull);
        btn.title = enFull ? 'Salir de pantalla completa' : 'Pantalla completa';
    }

    btn.addEventListener('click', function () {
        if (!document.fullscreenElement) {
            if (root.requestFullscreen) {
                root.requestFullscreen();
            } else if (root.webkitRequestFullscreen) {
                root.webkitRequestFullscreen();
            } else if (root.msRequestFullscreen) {
                root.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    });

    document.addEventListener('fullscreenchange', actualizarIcono);
    document.addEventListener('webkitfullscreenchange', actualizarIcono);
    document.addEventListener('msfullscreenchange', actualizarIcono);
    actualizarIcono();

    setTimeout(function () { window.location.reload(); }, 30000);
})();
JS
);