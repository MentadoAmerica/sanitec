<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MovimientosInventario $model */
/** @var string $tipo */

$esEntrada = $tipo === 'entrada';
$this->title = $esEntrada ? 'Registrar entrada' : 'Registrar entrega';
$this->params['breadcrumbs'][] = ['label' => 'Historial', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="admin-page">
    <header class="admin-page-header">
        <div>
            <span class="admin-eyebrow">Inventario</span>
            <h1 class="admin-page-title"><?= Html::encode($this->title) ?></h1>
            <p class="admin-page-subtitle">
                <?= $esEntrada
                    ? 'Registra la llegada de material al almacén.'
                    : 'Registra la salida de material hacia un edificio o intendente.' ?>
            </p>
        </div>
    </header>

    <div class="admin-card">
        <?= $this->render($esEntrada ? '_form-entrada' : '_form-salida', ['model' => $model]) ?>
    </div>
</section>