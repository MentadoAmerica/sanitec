<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $porTurno */
/** @var array $turnos */

$this->title = 'Horarios y asignaciones';
$this->params['breadcrumbs'][] = $this->title;

$dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Horarios y asignaciones</h1>
            <p class="admin-page-subtitle">Administra los horarios del personal de intendencia y de Servicio Social.</p>
        </div>
    </header>

    <nav class="admin-tabs" aria-label="Tipo de horario">
        <?= Html::a('Intendencia', ['horarios-personal/index'], ['class' => 'btn-tab-admin']) ?>
        <?= Html::a('Servicio Social', ['horarios-servicio-social/index'], ['class' => 'btn-tab-admin active']) ?>
    </nav>

    <header class="admin-subheader">
        <h2 class="admin-subtitle">Horarios de Servicio Social</h2>
        <?= Html::a(
            '<i class="bi bi-plus-lg me-2"></i>Nuevo turno',
            ['create'],
            ['class' => 'btn-primary-admin', 'encode' => false]
        ) ?>
    </header>

    <div class="admin-card">
        <?php if (empty($turnos)): ?>
            <div class="admin-empty-state">
                <i class="bi bi-mortarboard" aria-hidden="true"></i>
                <span>Aún no hay turnos de Servicio Social definidos. Crea el primero con "Nuevo turno".</span>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table admin-table admin-week-table align-middle mb-0">
                    <thead>
                        <tr class="admin-table-head">
                            <th>Turno</th>
                            <?php foreach ($dias as $dia): ?>
                                <th><?= Html::encode($dia) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($turnos as $turno): ?>
                            <tr>
                                <td class="fw-semibold"><?= Html::encode($turno) ?></td>
                                <?php foreach ($dias as $dia): ?>
                                    <?php $h = $porTurno[$turno][$dia] ?? null; ?>
                                    <td class="admin-week-cell">
                                        <?php if ($h): ?>
                                            <div class="admin-schedule-chip">
                                                <span class="admin-schedule-time">
                                                    <?= Html::encode(substr($h->hora_inicio, 0, 5)) ?>
                                                    -
                                                    <?= Html::encode(substr($h->hora_fin, 0, 5)) ?>
                                                </span>
                                                <div class="admin-schedule-actions">
                                                    <?= Html::a(
                                                        '<i class="bi bi-pencil"></i>',
                                                        ['update', 'id_horario_ss' => $h->id_horario_ss],
                                                        ['class' => 'admin-mini-btn', 'title' => 'Editar']
                                                    ) ?>
                                                    <?= Html::a(
                                                        '<i class="bi bi-x-lg"></i>',
                                                        ['delete', 'id_horario_ss' => $h->id_horario_ss],
                                                        [
                                                            'class' => 'admin-mini-btn admin-mini-danger',
                                                            'title' => 'Eliminar',
                                                            'data' => [
                                                                'confirm' => '¿Eliminar este turno?',
                                                                'method' => 'post',
                                                            ],
                                                        ]
                                                    ) ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <?= Html::a(
                                                '+',
                                                ['create', 'nombre_turno' => $turno, 'dia_semana' => $dia],
                                                ['class' => 'admin-add-schedule', 'title' => 'Agregar turno']
                                            ) ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>