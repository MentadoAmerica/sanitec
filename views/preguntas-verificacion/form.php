<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PreguntasVerificacion $model */
/** @var array $tiposReporte */
/** @var bool $esNuevo */

$this->title = $esNuevo ? 'Nueva pregunta de verificación' : 'Editar pregunta de verificación';
$this->params['breadcrumbs'][] = ['label' => 'Preguntas de verificación', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$opciones = $model->getOptions();
$triggers = $model->getReportTriggers();

$filas = [];
foreach ($opciones as $valor => $texto) {
    $filas[] = [
        'texto' => (string) $texto,
        'trigger' => isset($triggers[$valor]) ? (string) ($triggers[$valor][0] ?? '') : '',
    ];
}
if (empty($filas)) {
    $filas = [['texto' => '', 'trigger' => '']];
}
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Catálogo</span>
            <h1 class="admin-page-title"><?= Html::encode($this->title) ?></h1>
            <p class="admin-page-subtitle">
                Configura el texto, la sección y qué opciones generan reportes automáticos.
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a('Volver al listado', ['index'], ['class' => 'btn-cancel-admin']) ?>
        </div>
    </header>

    <?= Html::beginForm('', 'post', ['id' => 'pregunta-form']) ?>

    <div class="admin-card">
        <h2 class="h6 fw-bold mb-3">Datos de la pregunta</h2>

        <div class="row g-3">
            <div class="col-12">
                <label class="form-label" for="pregunta-texto">Pregunta</label>
                <textarea id="pregunta-texto"
                          name="PreguntasVerificacion[pregunta]"
                          class="form-control"
                          rows="3"
                          maxlength="500"
                          required
                          placeholder="Ej. ¿El piso del sanitario se encuentra limpio?"><?= Html::encode($model->pregunta) ?></textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label" for="pregunta-categoria">Sección</label>
                <select id="pregunta-categoria"
                        name="PreguntasVerificacion[categoria]"
                        class="form-select">
                    <option value="limpieza" <?= $model->categoria === 'limpieza' ? 'selected' : '' ?>>A. Limpieza</option>
                    <option value="abastecimiento" <?= $model->categoria === 'abastecimiento' ? 'selected' : '' ?>>B. Abastecimiento</option>
                    <option value="mantenimiento" <?= $model->categoria === 'mantenimiento' ? 'selected' : '' ?>>C. Mantenimiento</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label" for="pregunta-orden">Orden</label>
                <input type="number"
                       id="pregunta-orden"
                       name="PreguntasVerificacion[orden]"
                       class="form-control"
                       min="1"
                       value="<?= (int) $model->orden ?>">
                <div class="form-text">Posición dentro de la sección.</div>
            </div>

            <div class="col-md-3">
                <label class="form-label d-block">Obligatoria</label>
                <label class="switch-inline">
                    <input type="checkbox"
                           name="PreguntasVerificacion[obligatoria]"
                           value="1"
                           <?= $model->obligatoria ? 'checked' : '' ?>>
                    <span>El alumno debe responderla</span>
                </label>
            </div>

            <div class="col-md-3">
                <label class="form-label d-block">Estado</label>
                <label class="switch-inline">
                    <input type="checkbox"
                           name="PreguntasVerificacion[estado]"
                           value="1"
                           <?= $model->estado ? 'checked' : '' ?>>
                    <span>Activa en el formulario</span>
                </label>
            </div>
        </div>
    </div>

    <div class="admin-card mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="h6 fw-bold mb-1">Opciones de respuesta</h2>
                <p class="admin-muted-count mb-0">
                    Cada opción es lo que verá el alumno. Si eliges un reporte, esa opción genera un reporte automático.
                </p>
            </div>
            <button type="button" id="btn-add-opcion" class="btn-secondary-admin">
                <i class="bi bi-plus-lg me-1"></i>Agregar opción
            </button>
        </div>

        <div id="opciones-container" class="opciones-editor">
            <?php foreach ($filas as $fila): ?>
                <div class="opcion-row">
                    <span class="opcion-num"></span>
                    <input type="text"
                           class="form-control opcion-texto"
                           value="<?= Html::encode($fila['texto']) ?>"
                           placeholder="Texto de la opción"
                           maxlength="200">
                    <select class="form-select opcion-trigger">
                        <option value="">No genera reporte</option>
                        <?php foreach ($tiposReporte as $tipoNombre): ?>
                            <option value="<?= Html::encode($tipoNombre) ?>"
                                <?= $fila['trigger'] === $tipoNombre ? 'selected' : '' ?>>
                                Genera: <?= Html::encode($tipoNombre) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn-remove-opcion" title="Eliminar opción">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>

        <input type="hidden" name="opciones_json" id="opciones_json" value="">
    </div>

    <div class="admin-form-actions mt-3">
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn-cancel-admin']) ?>
        <button type="submit" class="btn-primary-admin">Guardar pregunta</button>
    </div>

    <?= Html::endForm() ?>
</section>

<?php
$this->registerJs(<<<JS
(function () {
    var container = document.getElementById('opciones-container');
    var btnAdd = document.getElementById('btn-add-opcion');
    var hidden = document.getElementById('opciones_json');
    var form = document.getElementById('pregunta-form');
    var primerSelect = container.querySelector('.opcion-trigger');

    function renumerar() {
        container.querySelectorAll('.opcion-row').forEach(function (row, i) {
            row.querySelector('.opcion-num').textContent = (i + 1);
        });
    }
    renumerar();

    function crearFila(texto, trigger) {
        var div = document.createElement('div');
        div.className = 'opcion-row';

        var num = document.createElement('span');
        num.className = 'opcion-num';
        div.appendChild(num);

        var input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control opcion-texto';
        input.placeholder = 'Texto de la opción';
        input.maxLength = 200;
        input.value = texto || '';
        div.appendChild(input);

        var select = document.createElement('select');
        select.className = 'form-select opcion-trigger';
        if (primerSelect) {
            Array.from(primerSelect.options).forEach(function (opt) {
                var o = new Option(opt.text, opt.value);
                if (opt.value === trigger) o.selected = true;
                select.add(o);
            });
        }
        div.appendChild(select);

        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn-remove-opcion';
        btn.title = 'Eliminar opción';
        btn.innerHTML = '<i class="bi bi-x-lg"></i>';
        div.appendChild(btn);

        container.appendChild(div);
        renumerar();
    }

    btnAdd.addEventListener('click', function () {
        crearFila('', '');
    });

    container.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-remove-opcion');
        if (!btn) return;
        var rows = container.querySelectorAll('.opcion-row');
        if (rows.length <= 1) {
            alert('Debe haber al menos una opción.');
            return;
        }
        btn.closest('.opcion-row').remove();
        renumerar();
    });

    form.addEventListener('submit', function (e) {
        var rows = container.querySelectorAll('.opcion-row');
        var resultado = [];
        var vacias = 0;
        rows.forEach(function (row) {
            var texto = row.querySelector('.opcion-texto').value.trim();
            var trigger = row.querySelector('.opcion-trigger').value;
            if (texto === '') { vacias++; return; }
            resultado.push({ texto: texto, trigger: trigger });
        });
        if (vacias > 0) {
            e.preventDefault();
            alert('Hay opciones vacías. Elimínalas o escribe su texto.');
            return;
        }
        if (resultado.length === 0) {
            e.preventDefault();
            alert('Agrega al menos una opción.');
            return;
        }
        hidden.value = JSON.stringify(resultado);
    });
})();
JS
);