<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\data\ActiveDataProvider $dataProvider */
$this->title = 'Encuestas de satisfacción';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Administración</span>
            <h1 class="admin-page-title">Encuestas de satisfacción</h1>
            <p class="admin-page-subtitle">Listado de respuestas registradas por sanitario y turno.</p>
        </div>
        <div class="admin-page-actions">
            <?= Html::a('Administrar preguntas', ['preguntas'], ['class' => 'btn-primary-admin']) ?>
        </div>
    </header>

    <div class="admin-card">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table admin-table align-middle mb-0'],
            'headerRowOptions' => ['class' => 'admin-table-head'],
            'summary' => 'Mostrando {begin} a {end} de {total} registros',
            'emptyText' => 'No hay encuestas registradas.',
            'columns' => [
                ['attribute' => 'id_evaluacion', 'label' => 'ID'],
                ['attribute' => 'edificio_nombre', 'label' => 'Edificio'],
                ['attribute' => 'sanitario_nombre', 'label' => 'Sanitario'],
                ['attribute' => 'turno', 'label' => 'Turno'],
                ['attribute' => 'fecha_hora', 'label' => 'Fecha y hora'],
                ['attribute' => 'calificacion_general', 'label' => 'Promedio'],
                ['attribute' => 'comentario', 'label' => 'Comentario'],
            ],
        ]); ?>
    </div>
</section>
