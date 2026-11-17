<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $grupos */
/** @var string $fecha */
/** @var bool $esAdmin */

$this->title = 'Rondas del día';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow"><?= $esAdmin ? 'Servicio Social' : 'Mis rondas' ?></span>
            <h1 class="admin-page-title">Rondas del día</h1>
            <p class="admin-page-subtitle">
                <?= Html::encode(Yii::$app->formatter->asDate($fecha, 'php:l d \d\e F \d\e Y')) ?>
            </p>
        </div>
        <?php if ($esAdmin): ?>
            <div class="admin-page-actions">
                <?= Html::a(
                    '<i class="bi bi-plus-lg me-2"></i>Programar ronda',
                    ['create'],
                    ['class' => 'btn-primary-admin', 'encode' => false]
                ) ?>
            </div>
        <?php endif; ?>
    </header>

    <?php if (empty($grupos)): ?>
        <div class="admin-card">
            <div class="admin-empty-state">
                <i class="bi bi-calendar-x" aria-hidden="true"></i>
                <span>No hay rondas programadas para hoy.</span>
                <?php if ($esAdmin): ?>
                    <?= Html::a('Programar la primera ronda', ['create'], ['class' => 'btn-secondary-admin mt-3']) ?>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($grupos as $grupo): ?>
            <div class="admin-card mb-3">
                <div class="admin-panel-heading">
                    <div>
                        <span class="admin-eyebrow">
                            <?= Html::encode($grupo['turno']) ?>
                            · <?= Html::encode($grupo['horario']) ?>
                        </span>
                        <h2 class="h5 mb-0">Edificio <?= Html::encode($grupo['edificio']) ?></h2>
                    </div>
                    <span class="admin-badge admin-badge-info">
                        <?= count($grupo['sanitarios']) ?> sanitarios
                    </span>
                </div>

                <div class="ronda-lista">
                    <?php foreach ($grupo['sanitarios'] as $s): ?>
                        <?php $revisado = !empty($s['id_verificacion']); ?>
                        <div class="ronda-item <?= $revisado ? 'ronda-item-ok' : '' ?>">
                            <div class="ronda-item-info">
                                <strong><?= Html::encode($s['sanitario_nombre']) ?></strong>
                               <div class="ronda-item-info">

</div>
                            </div>
                            <div class="ronda-item-actions">
                                <?php if ($revisado): ?>
                                    <span class="admin-badge admin-badge-active">
                                        <i class="bi bi-check2"></i> Revisado
                                    </span>
                                <?php else: ?>
                                    <?= Html::a(
                                        'Revisar <i class="bi bi-arrow-right ms-1"></i>',
                                        ['/verificaciones/create', 'id_ronda' => $s['id_ronda']],
                                        ['class' => 'btn-primary-admin', 'encode' => false]
                                    ) ?>
                                <?php endif; ?>

                                <?php if ($esAdmin): ?>
                                    <?= Html::a(
                                        '<i class="bi bi-pencil"></i>',
                                        ['update', 'id_ronda' => $s['id_ronda']],
                                        ['class' => 'btn-action-edit', 'title' => 'Editar ronda']
                                    ) ?>
                                    <?= Html::a(
                                        '<i class="bi bi-trash"></i>',
                                        ['delete', 'id_ronda' => $s['id_ronda']],
                                        [
                                            'class' => 'btn-action-delete',
                                            'title' => 'Eliminar ronda',
                                            'data' => [
                                                'confirm' => '¿Eliminar esta ronda?',
                                                'method' => 'post',
                                            ],
                                        ]
                                    ) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>