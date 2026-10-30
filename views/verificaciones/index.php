<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $verificaciones */

$this->title = 'Historial de verificaciones';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">SERVICIO SOCIAL</span>
            <h1 class="admin-page-title">Mi historial de verificaciones</h1>
            <p class="admin-page-subtitle">Rondas que has iniciado y resultados registrados.</p>
        </div>
        <?= Html::a('Ver rondas asignadas', ['/rondas-programadas/index'], ['class' => 'btn btn-outline-primary']) ?>
    </header>

    <div class="admin-card">
        <?php if ($verificaciones === []): ?>
            <div class="p-4 text-body-secondary">Aún no tienes verificaciones registradas.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Inicio</th>
                        <th>Edificio</th>
                        <th>Sanitario</th>
                        <th>Resultado</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($verificaciones as $verificacion): ?>
                        <tr>
                            <td><?= Html::encode(Yii::$app->formatter->asDatetime($verificacion['fecha_hora_inicio'], 'php:d/m/Y H:i')) ?></td>
                            <td><?= Html::encode($verificacion['edificio_nombre']) ?></td>
                            <td><?= Html::encode($verificacion['sanitario_nombre']) ?></td>
                            <td><?= Html::encode($verificacion['resultado_general'] ?? 'Pendiente') ?></td>
                            <td><?= $verificacion['fecha_hora_fin'] === null ? 'En curso' : 'Finalizada' ?></td>
                            <td class="text-end">
                                <?= Html::a(
                                    $verificacion['fecha_hora_fin'] === null ? 'Continuar' : 'Ver',
                                    $verificacion['fecha_hora_fin'] === null
                                        ? ['/verificaciones/finalizar', 'id_verificacion' => $verificacion['id_verificacion']]
                                        : ['/verificaciones/view', 'id_verificacion' => $verificacion['id_verificacion']],
                                    ['class' => 'btn btn-sm btn-outline-primary']
                                ) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
