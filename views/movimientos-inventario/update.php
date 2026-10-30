<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MovimientosInventario $model */

$this->title = 'Update Movimientos Inventario: ' . $model->id_movimiento;
$this->params['breadcrumbs'][] = ['label' => 'Movimientos Inventarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_movimiento, 'url' => ['view', 'id_movimiento' => $model->id_movimiento]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="movimientos-inventario-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
