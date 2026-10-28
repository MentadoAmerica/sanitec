<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Servicios $model */

$estaAbierto = $model->getEstaAbierto();
?>

<div class="admin-form">
    <?php $form = ActiveForm::begin(['id' => 'servicios-form']); ?>

    <div class="admin-form-section">
        <h3 class="admin-form-section-title">Observaciones</h3>
        <p class="admin-form-section-hint">
            Puedes agregar o modificar comentarios sobre este servicio. Los datos de
            entrada y salida no se pueden alterar desde aquí.
        </p>

        <?= $form->field($model, 'observaciones')->textarea([
            'rows' => 4,
            'placeholder' => 'Comentarios u observaciones sobre el servicio',
        ])->label(false) ?>
    </div>

    <?php if ($estaAbierto): ?>
        <div class="admin-form-section admin-form-section-warning">
            <h3 class="admin-form-section-title">Cerrar servicio manualmente</h3>
            <p class="admin-form-section-hint">
                Este servicio está abierto porque no se registró la salida. Si el
                intendente ya terminó, puedes cerrarlo manualmente. Quedará
                registrado que la corrección la hiciste tú.
            </p>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="hora_cierre_manual">Hora de cierre</label>
                    <input type="datetime-local"
                           id="hora_cierre_manual"
                           name="hora_cierre_manual"
                           class="form-control"
                           value="<?= date('Y-m-d\TH:i') ?>">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox"
                               name="cerrar_manual"
                               value="1"
                               id="cerrar_manual"
                               class="form-check-input">
                        <label for="cerrar_manual" class="form-check-label">
                            Cerrar el servicio ahora
                        </label>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="admin-form-actions">
        <?= Html::submitButton('Guardar', ['class' => 'btn-primary-admin']) ?>
        <?= Html::a('Cancelar', ['view', 'id_servicio' => $model->id_servicio], ['class' => 'btn-cancel-admin']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>