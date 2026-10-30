<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SolicitudesAbastecimiento[] $solicitudes */
/** @var bool $puedeActualizar */

$this->title = 'Solicitudes de insumos';
$this->params['breadcrumbs'][] = $this->title;
$estadosSiguientes = [
    'Pendiente' => ['En atención', 'Rechazada'],
    'En atención' => ['Atendida', 'Rechazada'],
];
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">SANITEC · ABASTECIMIENTO</span>
            <h1 class="admin-page-title">Solicitudes de insumos</h1>
            <p class="admin-page-subtitle">Consulta necesidades reportadas y actualiza su seguimiento.</p>
        </div>
    </header>

    <div class="admin-card">
        <?php if ($solicitudes === []): ?>
            <div class="p-4 text-body-secondary">No hay solicitudes de insumos registradas.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Material</th>
                        <th>Cantidad</th>
                        <th>Sanitario / reporte</th>
                        <th>Solicitante</th>
                        <th>Estado</th>
                        <?php if ($puedeActualizar): ?><th>Seguimiento</th><?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($solicitudes as $solicitud): ?>
                        <?php $siguientes = $estadosSiguientes[$solicitud->estado] ?? []; ?>
                        <tr>
                            <td><?= Html::encode(Yii::$app->formatter->asDatetime($solicitud->fecha_solicitud, 'php:d/m/Y H:i')) ?></td>
                            <td><?= Html::encode($solicitud->material ? $solicitud->material->nombre : 'Material eliminado') ?></td>
                            <td>
                                <?= Html::encode(Yii::$app->formatter->asDecimal($solicitud->cantidad_solicitada, 2)) ?>
                                <?= Html::encode($solicitud->material ? $solicitud->material->unidad_medida : '') ?>
                            </td>
                            <td>
                                <?php if ($solicitud->reporte !== null): ?>
                                    <?= Html::a(
                                        'Reporte #' . $solicitud->reporte->id_reporte,
                                        ['/reportes/view', 'id_reporte' => $solicitud->reporte->id_reporte]
                                    ) ?>
                                    <div class="small text-body-secondary">
                                        <?= Html::encode($solicitud->reporte->sanitario ? $solicitud->reporte->sanitario->nombre : '') ?>
                                    </div>
                                <?php else: ?>
                                    Sin reporte vinculado
                                <?php endif; ?>
                            </td>
                            <td><?= Html::encode($solicitud->usuarioSolicita ? $solicitud->usuarioSolicita->getNombreCompleto() : 'Usuario') ?></td>
                            <td><?= Html::encode($solicitud->estado) ?></td>
                            <?php if ($puedeActualizar): ?>
                                <td>
                                    <?php if ($siguientes !== []): ?>
                                        <?= Html::beginForm(['/solicitudes-abastecimiento/cambiar-estado', 'id_solicitud' => $solicitud->id_solicitud], 'post', ['class' => 'd-flex gap-2']) ?>
                                        <?= Html::dropDownList('nuevo_estado', null, array_combine($siguientes, $siguientes), ['class' => 'form-select form-select-sm']) ?>
                                        <?= Html::submitButton('Actualizar', ['class' => 'btn btn-sm btn-outline-primary']) ?>
                                        <?= Html::endForm() ?>
                                    <?php else: ?>
                                        <span class="text-body-secondary small">Sin acciones</span>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
