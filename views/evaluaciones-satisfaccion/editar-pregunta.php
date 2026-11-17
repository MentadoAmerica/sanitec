<?php

use yii\helpers\Html;

/** @var app\models\PreguntasSatisfaccion $model */

$this->title = 'Editar pregunta';
$this->params['breadcrumbs'][] = ['label' => 'Encuestas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Preguntas', 'url' => ['preguntas']];
$this->params['breadcrumbs'][] = $this->title;

$opciones = $model->getOptions();
$filas = [];
foreach ($opciones as $texto) {
    $filas[] = (string) $texto;
}
if (empty($filas)) {
    $filas = ['', ''];
}
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Encuestas</span>
            <h1 class="admin-page-title">Editar pregunta</h1>
            <p class="admin-page-subtitle">
                Los cambios aplican a encuestas nuevas. Las respuestas anteriores se conservan.
            </p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a('Volver al listado', ['preguntas'], ['class' => 'btn-cancel-admin']) ?>
        </div>
    </header>

    <?= Html::beginForm('', 'post', ['id' => 'pregunta-sat-form']) ?>

    <div class="admin-card">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label" for="pregunta-texto">Pregunta</label>
                <textarea id="pregunta-texto"
                          name="<?= $model->formName() ?>[pregunta]"
                          class="form-control"
                          rows="3"
                          maxlength="500"
                          required><?= Html::encode($model->pregunta) ?></textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tipo</label>
                <input class="form-control" value="<?= Html::encode(ucfirst((string) $model->categoria)) ?>" disabled>
                <div class="form-text">No editable.</div>
            </div>

            <div class="col-md-4">
                <label class="form-label d-block">Estado</label>
                <label class="switch-inline">
                    <input type="checkbox"
                           name="<?= $model->formName() ?>[estado]"
                           value="1"
                           <?= $model->estado ? 'checked' : '' ?>>
                    <span>Activa en la encuesta</span>
                </label>
            </div>

            <div class="col-md-4">
                <label class="form-label">Escala</label>
                <input class="form-control"
                       value="1 a <?= count($filas) ?>"
                       disabled>
                <div class="form-text">Se ajusta al número de opciones.</div>
            </div>
        </div>
    </div>

    <div class="admin-card mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="h6 fw-bold mb-1">Opciones de respuesta</h2>
                <p class="admin-muted-count mb-0">
                    Cada opción se numera automáticamente (1, 2, 3...). El orden es el que ve el usuario.
                </p>
            </div>
            <button type="button" id="btn-add-opcion-sat" class="btn-secondary-admin">
                <i class="bi bi-plus-lg me-1"></i>Agregar opción
            </button>
        </div>

        <div id="opciones-sat-container" class="opciones-editor">
            <?php foreach ($filas as $texto): ?>
                <div class="opcion-row">
                    <span class="opcion-num"></span>
                    <input type="text"
                           class="form-control opcion-texto"
                           value="<?= Html::encode($texto) ?>"
                           placeholder="Texto de la opción"
                           maxlength="200">
                    <button type="button" class="btn-remove-opcion" title="Eliminar opción">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>

        <input type="hidden" name="opciones_json" id="opciones-sat-json" value="">
    </div>

    <div class="admin-form-actions mt-3">
        <?= Html::a('Cancelar', ['preguntas'], ['class' => 'btn-cancel-admin']) ?>
        <button type="submit" class="btn-primary-admin">Guardar cambios</button>
    </div>

    <?= Html::endForm() ?>
</section>

<?php
$this->registerJs(<<<JS
(function () {
    var container = document.getElementById('opciones-sat-container');
    var btnAdd = document.getElementById('btn-add-opcion-sat');
    var hidden = document.getElementById('opciones-sat-json');
    var form = document.getElementById('pregunta-sat-form');

    function renumerar() {
        var rows = container.querySelectorAll('.opcion-row');
        rows.forEach(function (row, i) {
            row.querySelector('.opcion-num').textContent = (i + 1);
        });
    }
    renumerar();

    function crearFila(texto) {
        var div = document.createElement('div');
        div.className = 'opcion-row';
        div.innerHTML =
            '<span class="opcion-num"></span>' +
            '<input type="text" class="form-control opcion-texto" placeholder="Texto de la opción" maxlength="200" value="' + (texto || '').replace(/"/g, '&quot;') + '">' +
            '<button type="button" class="btn-remove-opcion" title="Eliminar opción"><i class="bi bi-x-lg"></i></button>';
        container.appendChild(div);
        renumerar();
    }

    btnAdd.addEventListener('click', function () { crearFila(''); });

    container.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-remove-opcion');
        if (!btn) return;
        var rows = container.querySelectorAll('.opcion-row');
        if (rows.length <= 2) {
            alert('Debe haber al menos dos opciones.');
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
            if (texto === '') { vacias++; return; }
            resultado.push({ texto: texto });
        });
        if (vacias > 0) {
            e.preventDefault();
            alert('Hay opciones vacías. Elimínalas o escribe su texto.');
            return;
        }
        if (resultado.length < 2) {
            e.preventDefault();
            alert('Agrega al menos dos opciones.');
            return;
        }
        hidden.value = JSON.stringify(resultado);
    });
})();
JS
);