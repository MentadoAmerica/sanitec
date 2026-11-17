<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PreguntasVerificacion[] $preguntas */

$this->title = 'Preguntas de verificación';
$this->params['breadcrumbs'][] = $this->title;

$etiquetasCategoria = [
    'limpieza' => 'A. Limpieza',
    'abastecimiento' => 'B. Abastecimiento',
    'mantenimiento' => 'C. Mantenimiento',
];

$grupos = ['limpieza' => [], 'abastecimiento' => [], 'mantenimiento' => []];
foreach ($preguntas as $p) {
    $g = $p->categoria ?: 'limpieza';
    if (!isset($grupos[$g])) {
        $grupos[$g] = [];
    }
    $grupos[$g][] = $p;
}
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Catálogo</span>
            <h1 class="admin-page-title">Preguntas de verificación</h1>
            <p class="admin-page-subtitle">
                <?= number_format(count($preguntas)) ?> preguntas en el formulario de inspección
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a(
                '<i class="bi bi-plus-lg me-2"></i>Nueva pregunta',
                ['create'],
                ['class' => 'btn-primary-admin', 'encode' => false]
            ) ?>
        </div>
    </header>

    <div class="admin-alert admin-alert-warning" style="margin-bottom:16px;">
        <i class="bi bi-info-circle-fill"></i>
        <div>
            Las preguntas están agrupadas en 3 secciones (A, B, C) que corresponden a las pestañas
            del formulario que llena el alumno de Servicio Social. Puedes editar el texto de cada
            opción y configurar qué opción genera un reporte automático.
        </div>
    </div>

    <?php foreach ($grupos as $categoria => $lista): ?>
        <?php if (empty($lista)) continue; ?>
        <div class="admin-card mb-3">
            <div class="admin-panel-heading">
                <div>
                    <span class="admin-eyebrow"><?= Html::encode($etiquetasCategoria[$categoria] ?? ucfirst($categoria)) ?></span>
                    <h2 class="h5 mb-0"><?= Html::encode(ucfirst($categoria)) ?></h2>
                </div>
                <span class="admin-badge admin-badge-info">
                    <?= count($lista) ?> preguntas
                </span>
            </div>

            <div class="preguntas-lista">
                <?php foreach ($lista as $p): ?>
                    <?php $opciones = $p->getOptions(); $triggers = $p->getReportTriggers(); ?>
                    <div class="pregunta-item">
                        <div class="pregunta-item-head">
                            <span class="pregunta-item-orden"><?= (int) $p->orden ?></span>
                            <div class="pregunta-item-cuerpo">
                                <strong><?= Html::encode($p->pregunta) ?></strong>
                                <div class="pregunta-item-badges">
                                    <?php if ($p->obligatoria): ?>
                                        <span class="admin-badge admin-badge-warning">Obligatoria</span>
                                    <?php endif; ?>
                                    <?php if ($p->estado): ?>
                                        <span class="admin-badge admin-badge-active">Activa</span>
                                    <?php else: ?>
                                        <span class="admin-badge admin-badge-inactive">Inactiva</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="pregunta-item-actions">
                                <?= Html::a('Editar', ['update', 'id_pregunta' => $p->id_pregunta], ['class' => 'btn-action-edit']) ?>
                                <?= Html::a('Eliminar', ['delete', 'id_pregunta' => $p->id_pregunta], [
                                    'class' => 'btn-action-delete',
                                    'data' => [
                                        'confirm' => '¿Eliminar esta pregunta? Si ya tiene respuestas, no se borrará.',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </div>

                        <div class="pregunta-opciones-lista">
                            <?php foreach ($opciones as $valor => $texto): ?>
                                <?php $tieneTrigger = isset($triggers[$valor]); ?>
                                <div class="pregunta-opcion <?= $tieneTrigger ? 'pregunta-opcion-trigger' : '' ?>">
                                    <span class="pregunta-opcion-num"><?= (int) $valor ?></span>
                                    <span class="pregunta-opcion-texto"><?= Html::encode($texto) ?></span>
                                    <?php if ($tieneTrigger): ?>
                                        <span class="pregunta-opcion-tag">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                            <?= Html::encode(implode(', ', $triggers[$valor])) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>