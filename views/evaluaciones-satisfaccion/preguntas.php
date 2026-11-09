<?php

use yii\helpers\Html;

/** @var app\models\PreguntasSatisfaccion[] $preguntas */
$this->title = 'Administrar encuesta';
$this->params['breadcrumbs'][] = ['label' => 'Encuestas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Administrar encuesta</h1>
            <p class="admin-page-subtitle">Edita las preguntas, opciones y estado que verá el público al escanear un QR.</p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a('Ver respuestas', ['index'], ['class' => 'btn-cancel-admin']) ?>
        </div>
    </header>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead class="admin-table-head">
                    <tr>
                        <th>Pregunta</th>
                        <th>Tipo</th>
                        <th>Opciones / escala</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($preguntas as $pregunta): ?>
                        <?php $opciones = $pregunta->getOptions(); ?>
                        <tr>
                            <td><?= Html::encode($pregunta->pregunta) ?></td>
                            <td><?= Html::encode(ucfirst((string) $pregunta->categoria)) ?></td>
                            <td>
                                <?php if ($pregunta->categoria === 'calificacion'): ?>
                                    <?= Html::encode($pregunta->escala_min . ' a ' . $pregunta->escala_max) ?>
                                <?php endif; ?>
                                <?= Html::encode(implode(', ', array_values($opciones))) ?>
                            </td>
                            <td>
                                <?php if ($pregunta->estado): ?>
                                    <span class="admin-badge admin-badge-active">Activa</span>
                                <?php else: ?>
                                    <span class="admin-badge admin-badge-inactive">Inactiva</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= Html::a('Editar', ['editar-pregunta', 'id' => $pregunta->id_pregunta], ['class' => 'btn-action-edit']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
