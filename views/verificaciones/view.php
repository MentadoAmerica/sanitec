<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Verificaciones $model */
/** @var app\models\RespuestasVerificacion[] $respuestas */
/** @var app\models\PreguntasVerificacion[] $preguntasPorId */
/** @var app\models\Reportes[] $reportes */

$this->title = 'Verificación #' . $model->id_verificacion;
$this->params['breadcrumbs'][] = ['label' => 'Rondas del día', 'url' => ['/rondas-programadas/index']];
$this->params['breadcrumbs'][] = $this->title;

$sanitario = $model->sanitario;
$edificio = $sanitario ? $sanitario->edificio : null;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Verificación #<?= Html::encode($model->id_verificacion) ?></span>
            <h1 class="admin-page-title">
                <?= Html::encode($sanitario ? $sanitario->nombre : 'Sanitario') ?>
            </h1>
            <p class="admin-page-subtitle">
                <?= Html::encode($edificio ? $edificio->nombre : '') ?>
                · <?= Html::encode(Yii::$app->formatter->asDatetime($model->fecha_hora_inicio, 'php:d/m/Y H:i')) ?>
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a('Rondas del día', ['/rondas-programadas/index'], ['class' => 'btn-cancel-admin']) ?>
        </div>
    </header>

    <?php if ($model->resultado_general === 'Cumple'): ?>
        <div class="admin-alert admin-alert-warning" style="background:#f4faf4;border-color:#c5ddb8;color:#2f6b3a;">
            <i class="bi bi-check-circle-fill"></i>
            <div>Sin incidencias detectadas. No se generaron reportes.</div>
        </div>
    <?php else: ?>
        <div class="admin-alert admin-alert-warning">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Se generaron <?= count($reportes) ?> reporte(s)</strong>
                que requieren atención del personal correspondiente.
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-12 col-lg-7">
            <div class="admin-card">
                <h2 class="h6 fw-bold mb-3">Respuestas registradas</h2>
                <table class="table admin-table mb-0">
                    <thead class="admin-table-head">
                        <tr>
                            <th>Pregunta</th>
                            <th>Respuesta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($respuestas as $r): ?>
                            <?php $p = $preguntasPorId[$r->id_pregunta] ?? null; ?>
                            <tr>
                                <td><?= Html::encode($p ? $p->pregunta : 'Pregunta #' . $r->id_pregunta) ?></td>
                                <td>
                                    <?php if ($p): ?>
                                        <?php $opciones = $p->getOptions(); ?>
                                        <?= Html::encode($opciones[$r->respuesta] ?? $r->respuesta) ?>
                                    <?php else: ?>
                                        <?= Html::encode($r->respuesta) ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($model->observaciones)): ?>
                <div class="admin-card mt-3">
                    <h2 class="h6 fw-bold mb-2">Observaciones</h2>
                    <p class="mb-0"><?= nl2br(Html::encode($model->observaciones)) ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-12 col-lg-5">
            <div class="admin-card">
                <h2 class="h6 fw-bold mb-3">Reportes generados</h2>
                <?php if (empty($reportes)): ?>
                    <p class="admin-muted-count mb-0">No se generaron reportes.</p>
                <?php else: ?>
                    <ul class="ronda-lista">
                        <?php foreach ($reportes as $rep): ?>
                            <li class="ronda-item">
                                <div class="ronda-item-info">
                                    <strong><?= Html::encode($rep->tipoReporte ? $rep->tipoReporte->nombre : 'Reporte #' . $rep->id_reporte) ?></strong>
                                    <span class="admin-muted-count"><?= Html::encode($rep->estado) ?></span>
                                </div>
                                <?= Html::a('Ver', ['/reportes/view', 'id_reporte' => $rep->id_reporte], ['class' => 'btn-action-view']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>