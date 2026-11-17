<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\RondasProgramadas $model */
/** @var app\models\HorariosServicioSocial[] $turnos */
/** @var array $sanitarios */

$this->title = 'Programar ronda';
$this->params['breadcrumbs'][] = ['label' => 'Rondas del día', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Servicio Social</span>
            <h1 class="admin-page-title">Programar ronda</h1>
            <p class="admin-page-subtitle">
                Selecciona fecha, turno y los sanitarios que se revisarán en esta ronda.
            </p>
        </div>
    </header>

    <?php if (empty($turnos)): ?>
        <div class="admin-alert admin-alert-warning">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>No hay turnos de servicio social activos.</strong>
                Antes de programar una ronda, crea un turno en
                <?= Html::a('Turnos de servicio social', ['/horarios-servicio-social/index'], ['class' => 'fw-semibold']) ?>.
            </div>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin([
        'id' => 'ronda-create-form',
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="admin-card">
        <h2 class="h6 fw-bold mb-3">Datos de la ronda</h2>

        <div class="row g-3">
            <div class="col-md-4">
                <?= $form->field($model, 'fecha_programada')->input('date', [
                    'id' => 'ronda-fecha',
                ])->hint('Día en que se realizará la ronda.') ?>
            </div>

            <div class="col-md-4">
                <label class="form-label" for="id_horario_ss">Turno</label>
                <select name="id_horario_ss" id="id_horario_ss" class="form-select" required>
                    <option value="">Selecciona un turno</option>
                    <?php foreach ($turnos as $t): ?>
                        <option value="<?= (int) $t->id_horario_ss ?>"
                                data-dia="<?= Html::encode($t->dia_semana) ?>">
                            <?= Html::encode($t->nombre_turno) ?>
                            · <?= Html::encode($t->dia_semana) ?>
                            · <?= Html::encode(substr($t->hora_inicio, 0, 5)) ?>-<?= Html::encode(substr($t->hora_fin, 0, 5)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">
                    Solo aparecen los turnos del día de la fecha elegida.
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label" for="estado">Estado inicial</label>
                <select name="RondasProgramadas[estado]" id="estado" class="form-select">
                    <option value="Pendiente" selected>Pendiente</option>
                    <option value="Programada">Programada</option>
                </select>
            </div>
        </div>
    </div>

    <div class="admin-card mt-3">
        <h2 class="h6 fw-bold mb-3">Sanitarios a revisar</h2>
        <p class="admin-muted-count mb-3">
            Marca los sanitarios que se incluirán en esta ronda.
        </p>

        <?php if (empty($sanitarios)): ?>
            <p class="admin-muted-count mb-0">
                No hay sanitarios activos.
                <?= Html::a('Crear sanitario', ['/sanitarios/create'], ['class' => 'fw-semibold']) ?>
            </p>
        <?php else: ?>
            <?php
            $porEdificio = [];
            foreach ($sanitarios as $s) {
                $porEdificio[$s['edificio']][] = $s;
            }
            ?>
            <div class="ronda-edificios">
                <?php foreach ($porEdificio as $edificio => $lista): ?>
                    <div class="ronda-edificio-bloque">
                        <div class="ronda-edificio-titulo">
                            <i class="bi bi-building"></i>
                            <?= Html::encode($edificio) ?>
                            <span class="admin-muted-count">(<?= count($lista) ?>)</span>
                        </div>
                        <div class="ronda-edificio-checkboxes">
                            <?php foreach ($lista as $s): ?>
                                <label class="ronda-checkbox">
                                    <input type="checkbox"
                                           name="sanitarios[]"
                                           value="<?= (int) $s['id_sanitario'] ?>">
                                    <span>
                                        <strong><?= Html::encode($s['nombre']) ?></strong>
                                        <?php if (!empty($s['tipo'])): ?>
                                            <span class="admin-muted-count">· <?= Html::encode($s['tipo']) ?></span>
                                        <?php endif; ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="admin-form-actions mt-3">
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn-cancel-admin']) ?>
        <?= Html::submitButton('Programar ronda', ['class' => 'btn-primary-admin']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</section>

<?php
$this->registerJs(<<<JS
(function () {
    var fecha = document.getElementById('ronda-fecha');
    var turno = document.getElementById('id_horario_ss');
    if (!fecha || !turno) return;

    var dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

    // guarda todas las opciones originales del select de turnos
    var opcionesOriginales = [];
    Array.from(turno.options).forEach(function (opt) {
        opcionesOriginales.push({
            value: opt.value,
            text: opt.text,
            dia: opt.dataset.dia || null
        });
    });

    function filtrarTurnos() {
        var valorFecha = fecha.value;
        if (!valorFecha) {
            // sin fecha: muestra todas
            turno.innerHTML = '';
            opcionesOriginales.forEach(function (o) {
                var opt = new Option(o.text, o.value);
                if (o.dia) opt.dataset.dia = o.dia;
                turno.add(opt);
            });
            turno.value = '';
            return;
        }

        var fechaObj = new Date(valorFecha + 'T12:00:00');
        var diaEspanol = dias[fechaObj.getDay()];

        turno.innerHTML = '';
        var placeholder = new Option('Selecciona un turno', '');
        turno.add(placeholder);

        var encontrados = 0;
        opcionesOriginales.forEach(function (o) {
            if (!o.dia) return;
            if (o.dia === diaEspanol) {
                var opt = new Option(o.text, o.value);
                opt.dataset.dia = o.dia;
                turno.add(opt);
                encontrados++;
            }
        });

        if (encontrados === 0) {
            var vacio = new Option('No hay turnos para ' + diaEspanol, '');
            vacio.disabled = true;
            turno.add(vacio);
        }

        turno.value = '';
    }

    fecha.addEventListener('change', filtrarTurnos);

    // filtra al cargar si ya hay fecha (por ejemplo, si volvió con un error)
    if (fecha.value) {
        filtrarTurnos();
    }
})();
JS
);