<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\RondasProgramadas $ronda */
/** @var array $agrupadas */

$this->title = 'Verificar sanitario';
$this->params['breadcrumbs'][] = ['label' => 'Rondas del día', 'url' => ['/rondas-programadas/index']];
$this->params['breadcrumbs'][] = $this->title;

$sanitario = $ronda->sanitario;
$edificio = $sanitario ? $sanitario->edificio : null;
$turno = $ronda->horarioSs;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Ronda en curso</span>
            <h1 class="admin-page-title">Verificar sanitario</h1>
            <p class="admin-page-subtitle">
                <?= Html::encode($turno ? $turno->nombre_turno : '') ?>
                <?php if ($turno): ?>
                    · <?= Html::encode($turno->dia_semana) ?>
                    · <?= Html::encode(substr($turno->hora_inicio, 0, 5)) ?>-<?= Html::encode(substr($turno->hora_fin, 0, 5)) ?>
                <?php endif; ?>
            </p>
        </div>
    </header>

    <!-- preview de lo que se va a revisar -->
    <div class="verif-preview">
        <div class="verif-preview-item">
            <span class="verif-preview-label">Edificio</span>
            <strong><?= Html::encode($edificio ? $edificio->nombre : '—') ?></strong>
        </div>
        <div class="verif-preview-item">
            <span class="verif-preview-label">Sanitario</span>
            <strong><?= Html::encode($sanitario ? $sanitario->nombre : '—') ?></strong>
        </div>
        <div class="verif-preview-item">
            <span class="verif-preview-label">Tipo</span>
            <strong><?= Html::encode($sanitario && $sanitario->tipo ? $sanitario->tipo : '—') ?></strong>
        </div>
        <div class="verif-preview-item">
            <span class="verif-preview-label">Fecha</span>
            <strong><?= Html::encode(Yii::$app->formatter->asDate($ronda->fecha_programada, 'php:d/m/Y')) ?></strong>
        </div>
    </div>

    <?= Html::beginForm(['store'], 'post', [
    'id' => 'verificacion-form',
    'enctype' => 'multipart/form-data',
]) ?>
    <input type="hidden" name="id_ronda" value="<?= (int) $ronda->id_ronda ?>">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">

    <ul class="nav nav-tabs verif-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-limpieza" type="button" role="tab">
                A. Limpieza y condiciones generales
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-abastecimiento" type="button" role="tab">
                B. Abastecimiento
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-mantenimiento" type="button" role="tab">
                C. Funcionamiento y mantenimiento
            </button>
        </li>
    </ul>

    <div class="tab-content verif-tab-content">
        <?php foreach (['limpieza', 'abastecimiento', 'mantenimiento'] as $indice => $grupo): ?>
            <div class="tab-pane fade <?= $indice === 0 ? 'show active' : '' ?>"
                 id="tab-<?= Html::encode($grupo) ?>" role="tabpanel">
                <?php if (empty($agrupadas[$grupo])): ?>
                    <p class="admin-muted-count">Sin preguntas en esta sección.</p>
                <?php else: ?>
                    <?php foreach ($agrupadas[$grupo] as $p): ?>
                        <fieldset class="verif-pregunta">
                            <legend class="verif-pregunta-titulo"><?= Html::encode($p->pregunta) ?></legend>
                            <div class="verif-opciones">
                                <?php foreach ($p->getOptions() as $valor => $etiqueta): ?>
                                    <label class="verif-opcion">
                                        <input type="radio"
                                               name="checklist[<?= (int) $p->id_pregunta ?>]"
                                               value="<?= Html::encode((string) $valor) ?>"
                                               <?= (int) $p->obligatoria === 1 ? 'required' : '' ?>>
                                        <span><?= Html::encode($etiqueta) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="admin-card mt-3">
        <h2 class="h6 fw-bold mb-2">Observaciones adicionales (opcional)</h2>
        <textarea name="observaciones" class="form-control" rows="3"
                  placeholder="Describe cualquier detalle relevante"></textarea>
    </div>
<div class="admin-card mt-3">
    <h2 class="h6 fw-bold mb-2">
        <i class="bi bi-camera me-1"></i>Evidencia fotográfica (opcional)
    </h2>
    <p class="admin-muted-count mb-3">
        Puedes adjuntar una foto del estado del sanitario. Esta foto quedará vinculada
        a los reportes que se generen a partir de esta verificación.
    </p>

    <label for="evidencia" class="btn-evidencia-upload">
        <i class="bi bi-upload"></i>
        <span id="evidencia-label">Seleccionar imagen o tomar foto</span>
        <input type="file"
               name="evidencia"
               id="evidencia"
               accept="image/jpeg,image/png,image/webp"
               capture="environment"
               style="display:none;">
    </label>

    <div class="form-text mt-2">
        Formatos: JPG, PNG o WEBP · Tamaño máximo: 5 MB
    </div>

    <div id="evidencia-preview-wrap" style="display:none;margin-top:14px;">
        <img id="evidencia-preview-img"
             alt="Vista previa de la evidencia"
             style="max-width:100%;max-height:320px;border-radius:8px;border:1px solid #e6dcd6;">
        <button type="button"
                id="evidencia-remove"
                class="btn btn-sm mt-2"
                style="background:#fbeaee;color:#7b1e2f;border:1px solid #e8c5cc;">
            <i class="bi bi-x-lg me-1"></i>Quitar foto
        </button>
    </div>
</div>
    <div class="admin-card mt-3">
        <h2 class="h6 fw-bold mb-2">PIN personal</h2>
        <p class="admin-muted-count mb-2">
            Ingresa tu PIN de alumno para que la verificación quede registrada a tu nombre.
        </p>
        <input type="text"
               name="pin"
               class="form-control verif-pin-input"
               inputmode="numeric"
               pattern="[0-9]{4,10}"
               maxlength="10"
               required
               placeholder="••••••">
    </div>

    <div class="admin-form-actions mt-3">
        <?= Html::a('Cancelar', ['/rondas-programadas/index'], ['class' => 'btn-cancel-admin']) ?>
        <button type="submit" class="btn-primary-admin">
            Guardar verificación
        </button>
    </div>

    <?= Html::endForm() ?>
</section>

<?php
// JS de preview de evidencia (va al final del archivo, después del registerJs de tabs)
$this->registerJs(<<<JS
(function () {
    var evidenciaInput = document.getElementById('evidencia');
    var evidenciaLabel = document.getElementById('evidencia-label');
    var evidenciaPreviewWrap = document.getElementById('evidencia-preview-wrap');
    var evidenciaPreviewImg = document.getElementById('evidencia-preview-img');
    var evidenciaRemove = document.getElementById('evidencia-remove');

    if (!evidenciaInput) {
        return;
    }

    evidenciaInput.addEventListener('change', function () {
        var file = this.files && this.files[0];
        if (!file) {
            evidenciaPreviewWrap.style.display = 'none';
            evidenciaLabel.textContent = 'Seleccionar imagen o tomar foto';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('La imagen excede 5 MB. Selecciona una más pequeña.');
            this.value = '';
            evidenciaPreviewWrap.style.display = 'none';
            evidenciaLabel.textContent = 'Seleccionar imagen o tomar foto';
            return;
        }
        evidenciaLabel.textContent = file.name;
        var reader = new FileReader();
        reader.onload = function (e) {
            evidenciaPreviewImg.src = e.target.result;
            evidenciaPreviewWrap.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    evidenciaRemove.addEventListener('click', function () {
        evidenciaInput.value = '';
        evidenciaLabel.textContent = 'Seleccionar imagen o tomar foto';
        evidenciaPreviewWrap.style.display = 'none';
        evidenciaPreviewImg.src = '';
    });
})();
JS
);