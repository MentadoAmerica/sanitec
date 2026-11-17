<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Edificios;

/** @var yii\web\View $this */
/** @var app\models\Sanitarios $model */

$this->title = 'Sanitario #' . $model->id_sanitario;
$this->params['breadcrumbs'][] = ['label' => 'Sanitarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

// Nombre del edificio (por si viene solo el id)
$edificioNombre = 'Sin asignar';
if (!empty($model->id_edificio)) {
    $edificio = Edificios::findOne($model->id_edificio);
    if ($edificio) {
        $edificioNombre = $edificio->nombre;
    }
}

// CSS extra para el DetailView con acentos guindo / vino
$this->registerCss(<<<CSS
.sanitarios-view .admin-page-title {
    color: #5c1a2e;
}
.sanitarios-view .admin-page-subtitle {
    color: #8a5566;
}
.sanitarios-view .admin-back-link {
    display: inline-flex;
    align-items: center;
    color: #7a1f3d;
    font-weight: 600;
    font-size: .85rem;
    text-decoration: none;
    margin-bottom: .35rem;
    transition: color .15s ease;
}
.sanitarios-view .admin-eyebrow {
    color: #7a1f3d;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    font-size: .75rem;
}
.sanitarios-view .admin-back-link:hover {
    color: #5c1a2e;
    text-decoration: underline;
}
.sanitarios-view .admin-detail-table th {
    width: 220px;
    color: #5c1a2e;
    font-weight: 600;
    background-color: #f9f1f4;
    border-color: #efdbe3;
}
.sanitarios-view .admin-detail-table td {
    color: #3b3b3b;
    border-color: #efdbe3;
    vertical-align: middle;
}
.sanitarios-view .admin-detail-table {
    border-color: #efdbe3;
    border-radius: .5rem;
    overflow: hidden;
}
CSS);
?>
<div class="sanitarios-view">

    <section class="admin-page">
         <div class="mb-2">
            <?= Html::a(
                '<i class="bi bi-arrow-left me-2"></i>Volver al listado',
                ['index'],
                ['class' => 'admin-back-link', 'encode' => false]
            ) ?>
        </div>
        <header class="admin-page-header">
            <div>
                <span class="admin-eyebrow">Detalle</span>
                <h1 class="admin-page-title"><?= Html::encode($this->title) ?></h1>
                <p class="admin-page-subtitle">
                    Información completa del sanitario registrado
                </p>
            </div>
            <div class="admin-page-actions">
                <?= Html::a(
                    '<i class="bi bi-pencil-square me-2"></i>Editar',
                    ['update', 'id_sanitario' => $model->id_sanitario],
                    ['class' => 'btn-action-edit', 'encode' => false]
                ) ?>
                <?= Html::a(
                    '<i class="bi bi-trash me-2"></i>Eliminar',
                    ['delete', 'id_sanitario' => $model->id_sanitario],
                    [
                        'class' => 'btn-action-delete',
                        'encode' => false,
                        'data' => [
                            'confirm' => '¿Confirmas eliminar este sanitario?',
                            'method' => 'post',
                        ],
                    ]
                ) ?>
            </div>
        </header>

        <div class="admin-card">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table admin-detail-table align-middle mb-0'],
                'attributes' => [
                    [
                        'attribute' => 'id_sanitario',
                        'label' => 'ID',
                        'value' => function ($m) {
                            return '#' . $m->id_sanitario;
                        },
                    ],
                    [
                        'attribute' => 'id_edificio',
                        'label' => 'Edificio',
                        'value' => $edificioNombre,
                    ],
                    [
                        'attribute' => 'nombre',
                        'label' => 'Nombre',
                        'contentOptions' => ['class' => 'fw-semibold'],
                    ],
                    [
                        'attribute' => 'tipo',
                        'label' => 'Tipo',
                    ],
                    [
                        'attribute' => 'ubicacion',
                        'label' => 'Ubicación',
                    ],
                    [
                        'attribute' => 'codigo_qr',
                        'label' => 'Código QR',
                        'format' => 'raw',
                        'value' => function ($m) {
                            $texto = $m->codigo_qr ?: 'sin código';
                            return '<code class="admin-code-chip">' . Html::encode($texto) . '</code>';
                        },
                    ],
                    [
                        'attribute' => 'estado',
                        'label' => 'Estado',
                        'format' => 'raw',
                        'value' => function ($m) {
                            return $m->estado
                                ? '<span class="admin-badge admin-badge-active">Activo</span>'
                                : '<span class="admin-badge admin-badge-inactive">Inactivo</span>';
                        },
                    ],
                ],
            ]) ?>
        </div>
    </section>

</div>