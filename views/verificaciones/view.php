<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Verificaciones $model */
/** @var array $checklist */
/** @var array $aspectos */

$this->title = 'Resultado de verificación';
$this->params['breadcrumbs'][] = ['label' => 'Historial', 'url' => ['/verificaciones/index']];
$this->params['breadcrumbs'][] = $this->title;
$sanitario = $model->sanitario;
$edificio = $sanitario !== null ? $sanitario->edificio : null;
$etiquetas = [
    'cumple' => 'Cumple',
    'requiere_atencion' => 'Requiere atención',
    'no_aplica' => 'No aplica',
];
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">SERVICIO SOCIAL · VERIFICACIÓN</span>
            <h1 class="admin-page-title"><?= Html::encode($sanitario ? $sanitario->nombre : 'Sanitario') ?></h1>
            <p class="admin-page-subtitle">
                <?= Html::encode($edificio ? $edificio->nombre : '') ?>
                · Iniciada <?= Html::encode(Yii::$app->formatter->asDatetime($model->fecha_hora_inicio, 'php:d/m/Y H:i')) ?>
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <?= Html::a('Historial', ['/verificaciones/index'], ['class' => 'btn btn-outline-secondary']) ?>
            <?php if ($model->fecha_hora_fin !== null): ?>
                <?= Html::a(
                    'Crear reporte de incidencia',
                    ['/reportes/create', 'id_verificacion' => $model->id_verificacion],
                    ['class' => 'btn btn-primary']
                ) ?>
            <?php endif; ?>
        </div>
    </header>

    <div class="admin-card p-4">
        <div class="row g-3 mb-4">
            <div class="col-md-4"><strong>Resultado:</strong> <?= Html::encode($model->resultado_general ?? 'En curso') ?></div>
            <div class="col-md-4"><strong>Inicio:</strong> <?= Html::encode(Yii::$app->formatter->asDatetime($model->fecha_hora_inicio, 'php:d/m/Y H:i')) ?></div>
            <div class="col-md-4"><strong>Finalización:</strong>
                <?= $model->fecha_hora_fin !== null
                    ? Html::encode(Yii::$app->formatter->asDatetime($model->fecha_hora_fin, 'php:d/m/Y H:i'))
                    : 'Pendiente' ?>
            </div>
        </div>

        <h2 class="h5">Lista de verificación</h2>
        <div class="table-responsive">
            <table class="table">
                <tbody>
                <?php foreach ($aspectos as $key => $label): ?>
                    <tr>
                        <th scope="row"><?= Html::encode($label) ?></th>
                        <td><?= Html::encode($etiquetas[$checklist[$key] ?? ''] ?? 'Sin registrar') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($model->observaciones)): ?>
            <h2 class="h5 mt-4">Observaciones</h2>
            <p><?= nl2br(Html::encode($model->observaciones)) ?></p>
        <?php endif; ?>

        <?php if (!empty($model->evidencia_foto)): ?>
            <h2 class="h5 mt-4">Evidencia fotográfica</h2>
            <?= Html::a(
                Html::img($model->evidencia_foto, [
                    'alt' => 'Evidencia de la verificación',
                    'class' => 'img-fluid rounded',
                    'style' => 'max-height: 360px;',
                ]),
                $model->evidencia_foto,
                ['target' => '_blank', 'rel' => 'noopener']
            ) ?>
        <?php endif; ?>
    </div>
</section>
