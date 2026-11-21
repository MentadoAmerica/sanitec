<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\RondasProgramadas $ronda */
/** @var array $agrupadas */
/** @var array $checklist */
/** @var string $observaciones */
/** @var array $errores */

$this->title = 'Verificar sanitario';
$this->params['breadcrumbs'][] = ['label' => 'Rondas del día', 'url' => ['/rondas-programadas/index']];
$this->params['breadcrumbs'][] = $this->title;

$sanitario = $ronda->sanitario;
$edificio = $sanitario ? $sanitario->edificio : null;
$turno = $ronda->horarioSs;

$errores = $errores ?? [];
$checklist = $checklist ?? [];
$observaciones = $observaciones ?? '';

// mapa de grupo -> id de tab (para que JS sepa a qué tab ir)
$mapaTabs = [
    'limpieza' => 'tab-limpieza',
    'abastecimiento' => 'tab-abastecimiento',
    'mantenimiento' => 'tab-mantenimiento',
];
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

    <!-- banner de errores del lado del servidor (si los hay) -->
    <?php if (!empty($errores)): ?>
        <div class="verif-errores-banner" id="verif-errores-banner">
            <div class="verif-errores-titulo">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <strong>Faltan datos por completar</strong>
            </div>
            <ul class="verif-errores-lista">
                <?php foreach ($errores as $err): ?>
                    <li><?= Html::encode($err['mensaje']) ?></li>
                <?php endforeach; ?>
            </ul>
            <div class="verif-errores-hint">Corrige los campos marcados en rojo.</div>
        </div>
    <?php endif; ?>

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
            <button class="nav-link active" id="tabbtn-limpieza" data-bs-toggle="tab" data-bs-target="#tab-limpieza" type="button" role="tab">
                A. Limpieza y condiciones
                <span class="tab-error-badge" id="badge-limpieza" style="display:none;"></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tabbtn-abastecimiento" data-bs-toggle="tab" data-bs-target="#tab-abastecimiento" type="button" role="tab">
                B. Abastecimiento
                <span class="tab-error-badge" id="badge-abastecimiento" style="display:none;"></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tabbtn-mantenimiento" data-bs-toggle="tab" data-bs-target="#tab-mantenimiento" type="button" role="tab">
                C. Funcionamiento y mantenimiento
                <span class="tab-error-badge" id="badge-mantenimiento" style="display:none;"></span>
            </button>
        </li>
    </ul>

    <div class="tab-content verif-tab-content">
        <?php foreach (['limpieza', 'abastecimiento', 'mantenimiento'] as $indice => $grupo): ?>
            <div class="tab-pane fade <?= $indice === 0 ? 'show active' : '' ?>"
                 id="tab-<?= Html::encode($grupo) ?>"
                 role="tabpanel"
                 data-grupo="<?= Html::encode($grupo) ?>">
                <?php if (empty($agrupadas[$grupo])): ?>
                    <p class="admin-muted-count">Sin preguntas en esta sección.</p>
                <?php else: ?>
                    <?php foreach ($agrupadas[$grupo] as $p): ?>
                        <?php $valorActual = $checklist[$p->id_pregunta] ?? null; ?>
                        <fieldset class="verif-pregunta"
                                  data-pregunta-id="<?= (int) $p->id_pregunta ?>"
                                  data-grupo="<?= Html::encode($grupo) ?>">
                            <legend class="verif-pregunta-titulo">
                                <?= Html::encode($p->pregunta) ?>
                                <?php if ((int) $p->obligatoria === 1): ?>
                                    <span class="verif-required">*</span>
                                <?php endif; ?>
                            </legend>
                            <div class="verif-opciones">
                                <?php foreach ($p->getOptions() as $valor => $etiqueta): ?>
                                    <label class="verif-opcion">
                                        <input type="radio"
                                               name="checklist[<?= (int) $p->id_pregunta ?>]"
                                               value="<?= Html::encode((string) $valor) ?>"
                                               <?= (string) $valorActual === (string) $valor ? 'checked' : '' ?>
                                               <?= (int) $p->obligatoria === 1 ? 'data-required="1"' : '' ?>>
                                        <span><?= Html::encode($etiqueta) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="verif-pregunta-error" style="display:none;">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span>Selecciona una opción.</span>
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
                  placeholder="Describe cualquier detalle relevante"><?= Html::encode($observaciones) ?></textarea>
    </div>

    <div class="admin-card mt-3">
        <h2 class="h6 fw-bold mb-2">Evidencia fotográfica (opcional)</h2>
        <p class="admin-muted-count mb-3">
            Adjunta una foto del estado del sanitario. Se vinculará a los reportes que se generen.
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
                 alt="Vista previa"
                 style="max-width:100%;max-height:320px;border-radius:8px;border:1px solid #e6dcd6;">
            <button type="button" id="evidencia-remove"
                    class="btn btn-sm mt-2"
                    style="background:#fbeaee;color:#7b1e2f;border:1px solid #e8c5cc;">
                <i class="bi bi-x-lg me-1"></i>Quitar foto
            </button>
        </div>
    </div>

    <div class="admin-card mt-3" id="pin-card">
        <h2 class="h6 fw-bold mb-2">PIN personal</h2>
        <p class="admin-muted-count mb-2">
            Ingresa tu PIN de alumno para que la verificación quede registrada a tu nombre.
        </p>
        <input type="text"
               name="pin"
               id="verif-pin"
               class="form-control verif-pin-input"
               inputmode="numeric"
               pattern="[0-9]{4,10}"
               maxlength="10"
               placeholder="••••••"
               autocomplete="off">
        <div class="verif-pin-error" id="verif-pin-error" style="display:none;">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>Debes ingresar tu PIN de 6 dígitos.</span>
        </div>
    </div>

    <div class="admin-form-actions mt-3">
        <?= Html::a('Cancelar', ['/rondas-programadas/index'], ['class' => 'btn-cancel-admin']) ?>
        <button type="submit" class="btn-primary-admin" id="btn-guardar-verif">
            Guardar verificación
        </button>
    </div>

    <?= Html::endForm() ?>
</section>

<?php
// error del servidor: pasar al JS para que marque campos específicos
$erroresJson = json_encode($errores, JSON_UNESCAPED_UNICODE);
$mapaTabsJson = json_encode($mapaTabs, JSON_UNESCAPED_UNICODE);
$this->registerJs(<<<JS
(function () {
    var form = document.getElementById('verificacion-form');
    var pinInput = document.getElementById('verif-pin');
    var pinError = document.getElementById('verif-pin-error');
    var pinCard = document.getElementById('pin-card');
    var btnGuardar = document.getElementById('btn-guardar-verif');
    var mapaTabs = {$mapaTabsJson};
    var erroresServidor = {$erroresJson};

    // ===== utilidades =====

    function activarTab(grupo) {
        var id = mapaTabs[grupo];
        if (!id) return;
        var btn = document.querySelector('button[data-bs-target="#' + id + '"]');
        if (!btn) return;
        var tab = new bootstrap.Tab(btn);
        tab.show();
    }

    function scrollYFoco(el) {
        if (!el) return;
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        var focusable = el.querySelector('input:not([type=hidden]), textarea, select');
        if (focusable) {
            setTimeout(function () { focusable.focus({ preventScroll: true }); }, 350);
        }
    }

    function marcarPreguntaInvalida(fieldset) {
        fieldset.classList.add('verif-pregunta-invalida');
        var err = fieldset.querySelector('.verif-pregunta-error');
        if (err) err.style.display = 'flex';
    }

    function limpiarPreguntaInvalida(fieldset) {
        fieldset.classList.remove('verif-pregunta-invalida');
        var err = fieldset.querySelector('.verif-pregunta-error');
        if (err) err.style.display = 'none';
    }

    function marcarPinInvalido(mensaje) {
        pinInput.classList.add('is-invalid');
        pinCard.classList.add('verif-pin-card-invalida');
        pinError.querySelector('span').textContent = mensaje || 'Debes ingresar tu PIN.';
        pinError.style.display = 'flex';
    }

    function limpiarPinInvalido() {
        pinInput.classList.remove('is-invalid');
        pinCard.classList.remove('verif-pin-card-invalida');
        pinError.style.display = 'none';
    }

    // ===== contar errores por tab para badges =====

    function actualizarBadges() {
        var grupos = ['limpieza', 'abastecimiento', 'mantenimiento'];
        grupos.forEach(function (grupo) {
            var tab = document.getElementById('tab-' + grupo);
            if (!tab) return;
            var faltantes = tab.querySelectorAll('.verif-pregunta-invalida').length;
            var badge = document.getElementById('badge-' + grupo);
            if (!badge) return;
            if (faltantes > 0) {
                badge.textContent = faltantes;
                badge.style.display = 'inline-flex';
            } else {
                badge.style.display = 'none';
            }
        });
    }

    // ===== validación al enviar =====

    form.addEventListener('submit', function (e) {
        var primerError = null;
        var tabConError = null;
        var totalErrores = 0;

        // 1. validar preguntas obligatorias
        var fieldsets = form.querySelectorAll('.verif-pregunta');
        fieldsets.forEach(function (fs) {
            var radios = fs.querySelectorAll('input[type="radio"]');
            if (radios.length === 0) return;
            var requiere = radios[0].getAttribute('data-required') === '1';
            if (!requiere) return;

            var seleccionado = fs.querySelector('input[type="radio"]:checked');
            if (!seleccionado) {
                marcarPreguntaInvalida(fs);
                totalErrores++;
                if (!primerError) {
                    primerError = fs;
                    tabConError = fs.getAttribute('data-grupo');
                }
            } else {
                limpiarPreguntaInvalida(fs);
            }
        });

        // 2. validar PIN
        var pinValor = (pinInput.value || '').trim();
        if (!/^[0-9]{4,10}\$/.test(pinValor)) {
            marcarPinInvalido(pinValor === '' ? 'Debes ingresar tu PIN.' : 'El PIN debe ser numérico (4 a 10 dígitos).');
            totalErrores++;
            if (!primerError) {
                primerError = pinCard;
            }
        } else {
            limpiarPinInvalido();
        }

        actualizarBadges();

        if (totalErrores === 0) {
            // todo ok, dejar que el form envíe
            btnGuardar.disabled = true;
            btnGuardar.textContent = 'Guardando...';
            return;
        }

        // hay errores: bloquear envío
        e.preventDefault();

        // ir al tab con error (si no es el actual)
        if (tabConError) {
            activarTab(tabConError);
            setTimeout(function () {
                scrollYFoco(primerError);
            }, 200);
        } else {
            // solo el pin: scroll directo
            scrollYFoco(primerError);
        }
    });

    // ===== limpiar errores al corregir =====

    form.addEventListener('change', function (e) {
        if (e.target.type === 'radio') {
            var fs = e.target.closest('.verif-pregunta');
            if (fs) limpiarPreguntaInvalida(fs);
            actualizarBadges();
        }
    });

    pinInput.addEventListener('input', function () {
        if (/^[0-9]{4,10}\$/.test(pinInput.value.trim())) {
            limpiarPinInvalido();
        }
    });

    // ===== auto-marcar errores del servidor (si los hay) =====

    if (erroresServidor && erroresServidor.length > 0) {
        var primero = null;
        var primerGrupo = null;

        erroresServidor.forEach(function (err) {
            if (err.tipo === 'pregunta' && err.id_pregunta) {
                var fs = form.querySelector('.verif-pregunta[data-pregunta-id="' + err.id_pregunta + '"]');
                if (fs) {
                    marcarPreguntaInvalida(fs);
                    if (!primero) {
                        primero = fs;
                        primerGrupo = fs.getAttribute('data-grupo');
                    }
                }
            } else if (err.tipo === 'pin') {
                marcarPinInvalido(err.mensaje);
                if (!primero) {
                    primero = pinCard;
                }
            }
        });

        actualizarBadges();

        if (primerGrupo) {
            activarTab(primerGrupo);
            setTimeout(function () { scrollYFoco(primero); }, 200);
        } else if (primero) {
            scrollYFoco(primero);
        }

        // mostrar banner arriba
        var banner = document.getElementById('verif-errores-banner');
        if (banner) {
            banner.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // ===== evidencia (preview) =====

    var evidenciaInput = document.getElementById('evidencia');
    var evidenciaLabel = document.getElementById('evidencia-label');
    var evidenciaPreviewWrap = document.getElementById('evidencia-preview-wrap');
    var evidenciaPreviewImg = document.getElementById('evidencia-preview-img');
    var evidenciaRemove = document.getElementById('evidencia-remove');

    if (evidenciaInput) {
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
    }
})();
JS
);