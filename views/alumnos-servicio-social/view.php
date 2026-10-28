<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\AlumnosServicioSocial $model */
/** @var array $turnos */
/** @var int $verificaciones */

$this->title = 'Alumno #' . $model->id_alumno;
$this->params['breadcrumbs'][] = ['label' => 'Alumnos de servicio social', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$usuario = $model->usuario;
$nombre = $usuario ? $usuario->getNombreCompleto() : 'Alumno sin cuenta';
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Alumno #<?= Html::encode($model->id_alumno) ?></span>
            <h1 class="admin-page-title"><?= Html::encode($nombre) ?></h1>
            <p class="admin-page-subtitle">
                <?= Html::encode($model->matricula ?: 'sin matrícula') ?>
                · <?= $model->estado ? 'activo' : 'inactivo' ?>
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a('Editar', ['update', 'id_alumno' => $model->id_alumno], ['class' => 'btn-primary-admin']) ?>
            <?= Html::a('Volver al listado', ['index'], ['class' => 'btn-cancel-admin']) ?>
        </div>
    </header>

    <div class="row g-3">
        <div class="col-12 col-lg-7">
            <div class="admin-card">
                <h3 class="admin-form-section-title mb-3">Datos del alumno</h3>
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table admin-table admin-detail-table mb-0'],
                    'attributes' => [
                        [
                            'label' => 'Nombre completo',
                            'value' => $nombre,
                        ],
                        [
                            'label' => 'Correo',
                            'value' => $usuario ? $usuario->correo : '—',
                        ],
                        [
                            'label' => 'Teléfono',
                            'value' => $usuario && $usuario->telefono ? $usuario->telefono : '—',
                        ],
                        [
                            'attribute' => 'matricula',
                            'label' => 'Matrícula',
                            'format' => 'raw',
                            'value' => $model->matricula
                                ? '<code class="admin-code-chip">' . Html::encode($model->matricula) . '</code>'
                                : '<span class="admin-muted-count">sin matrícula</span>',
                        ],
                        [
                            'attribute' => 'estado',
                            'label' => 'Estado',
                            'format' => 'raw',
                            'value' => $model->estado
                                ? '<span class="admin-badge admin-badge-active">Activo</span>'
                                : '<span class="admin-badge admin-badge-inactive">Inactivo</span>',
                        ],
                        [
                            'label' => 'Cuenta vinculada',
                            'value' => $usuario
                                ? 'usuario #' . $usuario->id_usuario . ' · ' . $usuario->correo
                                : '—',
                        ],
                    ],
                ]); ?>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="admin-card mb-3">
                <h3 class="admin-form-section-title">Actividad</h3>
                <div class="admin-stat-grid" style="grid-template-columns: 1fr 1fr;">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Verificaciones</div>
                        <div class="admin-stat-value"><?= number_format($verificaciones) ?></div>
                    </div>
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Turnos asignados</div>
                        <div class="admin-stat-value"><?= number_format(count($turnos)) ?></div>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <h3 class="admin-form-section-title mb-3">Turnos asignados</h3>
                <?php if (empty($turnos)): ?>
                    <p class="admin-muted-count mb-0">
                        Este alumno aún no está inscrito en ningún turno.
                        <?= Html::a('Inscribir ahora', ['/alumno-horarios-ss/create', 'id_alumno' => $model->id_alumno], ['class' => 'fw-semibold']) ?>
                    </p>
                <?php else: ?>
                    <ul class="admin-schedule-list">
                        <?php foreach ($turnos as $t): ?>
                            <li class="admin-schedule-list-item">
                                <div>
                                    <strong><?= Html::encode($t['nombre_turno']) ?></strong>
                                    <div class="admin-muted-count">
                                        <?= Html::encode($t['dia_semana']) ?>
                                        · <?= Html::encode(substr($t['hora_inicio'], 0, 5)) ?>-<?= Html::encode(substr($t['hora_fin'], 0, 5)) ?>
                                    </div>
                                </div>
                                <span class="admin-badge admin-badge-active">activo</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>