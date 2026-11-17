<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Edificios $model */

$this->title = 'Edificio #' . $model->id_edificio;
$this->params['breadcrumbs'][] = ['label' => 'Edificios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

// Paleta guindo/vino para esta vista (sin línea separadora)
$this->registerCss(<<<CSS
.edificios-view .admin-eyebrow {
    color: #7a1f3d;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    font-size: .75rem;
}
.edificios-view .admin-page-title {
    color: #5c1a2e;
}
.edificios-view .admin-page-subtitle {
    color: #8a5566;
}
.edificios-view .admin-back-link {
    display: inline-flex;
    align-items: center;
    color: #7a1f3d;
    font-weight: 600;
    font-size: .85rem;
    text-decoration: none;
    margin-bottom: .35rem;
    transition: color .15s ease;
}
.edificios-view .admin-back-link:hover {
    color: #5c1a2e;
    text-decoration: underline;
}
.edificios-view .admin-card {
    border: 1px solid #e6c8d3;
    border-top: 4px solid #7a1f3d;
    border-radius: .6rem;
    background: #fff;
    padding: 0;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(122, 31, 61, .06);
}
.edificios-view .admin-detail-table {
    margin-bottom: 0;
    border-color: #efdbe3;
}
.edificios-view .admin-detail-table th {
    width: 220px;
    color: #7a1f3d;
    font-weight: 700;
    background-color: #f7e9ee;
    border-color: #efdbe3;
    padding: .9rem 1.1rem;
}
.edificios-view .admin-detail-table td {
    color: #3b3b3b;
    border-color: #efdbe3;
    vertical-align: middle;
    padding: .9rem 1.1rem;
}
.edificios-view .admin-detail-table tr:hover td {
    background-color: #fcf6f8;
}
CSS);
?>
<div class="edificios-view">

    <section class="admin-page">

        <!-- Botón regresar ARRIBA del título -->
        <div class="mb-2">
            <?= Html::a(
                '<i class="bi bi-arrow-left me-2"></i>Volver al listado',
                ['index'],
                ['class' => 'admin-back-link', 'encode' => false]
            ) ?>
        </div>

        <header class="admin-page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="admin-eyebrow">Detalle</span>
                <h1 class="admin-page-title"><?= Html::encode($this->title) ?></h1>
                <p class="admin-page-subtitle mb-0">
                    Información completa del edificio registrado
                </p>
            </div>
            <div class="admin-page-actions d-flex gap-2 flex-wrap">
                <?= Html::a(
                    '<i class="bi bi-pencil-square me-2"></i>Editar',
                    ['update', 'id_edificio' => $model->id_edificio],
                    ['class' => 'btn-action-edit', 'encode' => false]
                ) ?>
                <?= Html::a(
                    '<i class="bi bi-trash me-2"></i>Eliminar',
                    ['delete', 'id_edificio' => $model->id_edificio],
                    [
                        'class' => 'btn-action-delete',
                        'encode' => false,
                        'data' => [
                            'confirm' => '¿Confirmas eliminar este edificio?',
                            'method' => 'post',
                        ],
                    ]
                ) ?>
            </div>
        </header>

        <div class="admin-card">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table admin-detail-table align-middle'],
                'attributes' => [
                    [
                        'attribute' => 'id_edificio',
                        'label' => 'ID',
                        'value' => function ($m) {
                            return '#' . $m->id_edificio;
                        },
                    ],
                    [
                        'attribute' => 'nombre',
                        'label' => 'Nombre',
                        'contentOptions' => ['class' => 'fw-semibold'],
                    ],
                    [
                        'attribute' => 'descripcion',
                        'label' => 'Descripción',
                        'format' => 'ntext',
                    ],
                    [
                        'attribute' => 'ubicacion',
                        'label' => 'Ubicación',
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