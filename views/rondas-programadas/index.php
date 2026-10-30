<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $rondas */
/** @var int|null $idAlumno */
/** @var bool $esAdministrador */

$this->title = $esAdministrador ? 'Rondas programadas' : 'Mis rondas';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">SERVICIO SOCIAL</span>
            <h1 class="admin-page-title"><?= $esAdministrador ? 'Rondas programadas' : 'Mis rondas de verificación' ?></h1>
            <p class="admin-page-subtitle">
                <?= $esAdministrador
                    ? 'Programa rondas y consulta su estado.'
                    : 'Consulta tus rondas asignadas e inicia la inspección del sanitario.' ?>
            </p>
        </div>
        <?php if ($esAdministrador): ?>
            <?= Html::a('Programar ronda', ['/rondas-programadas/create'], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
    </header>

    <?php if ($rondas === []): ?>
        <div class="admin-card p-4">
            <h2 class="h5">No hay rondas asignadas</h2>
            <p class="mb-0 text-body-secondary">Cuando se programe una ronda para tu horario, aparecerá aquí.</p>
        </div>
    <?php else: ?>
        <div class="admin-card">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Fecha y hora</th>
                        <th>Edificio</th>
                        <th>Sanitario</th>
                        <?php if ($esAdministrador): ?><th>Horario</th><?php endif; ?>
                        <th>Estado</th>
                        <th class="text-end">Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rondas as $ronda): ?>
                        <?php
                        $estado = mb_strtolower($ronda['estado'], 'UTF-8');
                        $verificacionPropia = $ronda['id_verificacion'] !== null
                            && (int) $ronda['alumno_verificador'] === $idAlumno;
                        ?>
                        <tr>
                            <td>
                                <?= Html::encode(Yii::$app->formatter->asDate($ronda['fecha_programada'], 'php:d/m/Y')) ?>
                                <span class="text-body-secondary">· <?= Html::encode(substr($ronda['hora_programada'], 0, 5)) ?></span>
                            </td>
                            <td><?= Html::encode($ronda['edificio_nombre']) ?></td>
                            <td><?= Html::encode($ronda['sanitario_nombre']) ?></td>
                            <?php if ($esAdministrador): ?><td><?= Html::encode($ronda['nombre_turno']) ?></td><?php endif; ?>
                            <td>
                                <span class="badge text-bg-<?= $estado === 'completada' ? 'success' : ($estado === 'en curso' ? 'info' : 'secondary') ?>">
                                    <?= Html::encode($ronda['estado']) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <?php if ($esAdministrador): ?>
                                    <?= $ronda['id_verificacion'] !== null ? 'Verificación registrada' : 'Pendiente de realizar' ?>
                                <?php elseif ($ronda['id_verificacion'] !== null && $verificacionPropia): ?>
                                    <?= Html::a(
                                        $ronda['fecha_hora_fin'] === null ? 'Continuar' : 'Ver resultado',
                                        $ronda['fecha_hora_fin'] === null
                                            ? ['/verificaciones/finalizar', 'id_verificacion' => $ronda['id_verificacion']]
                                            : ['/verificaciones/view', 'id_verificacion' => $ronda['id_verificacion']],
                                        ['class' => 'btn btn-sm btn-outline-primary']
                                    ) ?>
                                <?php elseif ($ronda['id_verificacion'] !== null): ?>
                                    <span class="text-body-secondary small">Ya atendida</span>
                                <?php elseif ($estado !== 'completada'): ?>
                                    <?= Html::beginForm(['/rondas-programadas/iniciar', 'id_ronda' => $ronda['id_ronda']], 'post', ['class' => 'd-inline']) ?>
                                    <?= Html::submitButton('Iniciar ronda', ['class' => 'btn btn-sm btn-primary']) ?>
                                    <?= Html::endForm() ?>
                                <?php else: ?>
                                    <span class="text-body-secondary small">Completada</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</section>
