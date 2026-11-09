<?php

use yii\helpers\Html;

/** @var app\models\Sanitarios $sanitario */
/** @var app\models\Edificios $edificio */
/** @var app\models\PreguntasSatisfaccion[] $preguntas */
/** @var string $token */

$this->title = 'Encuesta de satisfacción';
?>
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-lg-5">
            <div class="mb-4">
                <h1 class="h3 mb-1">ENCUESTA DE SATISFACCIÓN Y CALIDAD – Evaluación de Instalaciones Sanitarias</h1>
                <p class="mb-0 text-muted">Edificio: <strong><?= Html::encode($edificio ? $edificio->nombre : 'Sin asignar') ?></strong> · Sanitario: <strong><?= Html::encode($sanitario->nombre) ?></strong> · Planta/ubicación: <strong><?= Html::encode($sanitario->ubicacion ?: 'General') ?></strong></p>
            </div>

            <form method="post" action="<?= Html::encode(\yii\helpers\Url::to(['/encuestas/guardar'])) ?>" onsubmit="this.querySelector('button[type=submit]').disabled = true;">
                <input type="hidden" name="_csrf" value="<?= Html::encode(\Yii::$app->request->csrfToken) ?>" />
                <input type="hidden" name="token" value="<?= Html::encode($token) ?>" />

                <?php foreach ($preguntas as $index => $pregunta): ?>
                    <?php $idPregunta = (int) $pregunta->id_pregunta; ?>
                    <?php $name = 'pregunta_' . $idPregunta; ?>
                    <?php $opciones = $pregunta->getOptions(); ?>
                    <div class="mb-4 border-bottom pb-3">
                        <p class="fw-semibold mb-3"><?= Html::encode($index + 1) ?>. <?= Html::encode($pregunta->pregunta) ?></p>

                        <?php if ($pregunta->categoria === 'mantenimiento'): ?>
                            <div class="row g-2">
                                <?php foreach ($opciones as $value => $label): ?>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="d-flex align-items-center gap-2 border rounded px-3 py-2">
                                            <input type="checkbox" name="<?= Html::encode($name) ?>[]" value="<?= Html::encode((string) $value) ?>" />
                                            <span><?= Html::encode($label) ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php elseif ($pregunta->categoria === 'frecuencia'): ?>
                            <div class="row g-2">
                                <?php foreach ($opciones as $value => $label): ?>
                                    <div class="col-md-4">
                                        <label class="d-flex align-items-center gap-2 border rounded px-3 py-2">
                                            <input type="radio" name="<?= Html::encode($name) ?>" value="<?= Html::encode((string) $value) ?>" required />
                                            <span><?= Html::encode($label) ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="row g-2">
                                <?php foreach ($opciones as $valor => $label): ?>
                                    <div class="col-6 col-md-2">
                                        <label class="d-flex align-items-center justify-content-center gap-2 border rounded px-2 py-2 text-center h-100">
                                            <input type="radio" name="<?= Html::encode($name) ?>" value="<?= Html::encode((string) $valor) ?>" required />
                                            <span><?= Html::encode((string) $valor) ?> - <?= Html::encode($label) ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <div class="mb-4">
                    <label class="form-label fw-semibold" for="comentario">Comentarios o sugerencias adicionales (opcional)</label>
                    <textarea id="comentario" name="comentario" class="form-control" rows="4" placeholder="Escribe tu comentario..."></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg">Enviar encuesta</button>
                </div>
            </form>
        </div>
    </div>
</div>
