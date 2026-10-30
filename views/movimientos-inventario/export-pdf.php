<?php

use yii\helpers\Html;

/** @var array $rows */
/** @var string|null $tipo */
/** @var string|null $desde */
/** @var string|null $hasta */

$titulo = 'Movimientos de inventario';
if ($tipo === 'entrada') $titulo .= ' · Entradas';
if ($tipo === 'salida') $titulo .= ' · Salidas';
$periodo = '';
if ($desde || $hasta) {
    $periodo = 'Periodo: ' . ($desde ?: 'inicio') . ' — ' . ($hasta ?: 'hoy');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Movimientos SANITEC</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #2c1e22; }
        h1 { color: #7b1e2f; font-size: 16px; margin: 0 0 4px; }
        .meta { color: #8a7a72; margin-bottom: 12px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f2ebe3; color: #5a1522; text-align: left; padding: 6px 4px; font-size: 10px; border-bottom: 1px solid #e6dcd6; }
        td { padding: 5px 4px; border-bottom: 1px solid #f0e8e2; font-size: 10px; }
        .badge-entrada { color: #2f6b3a; font-weight: bold; }
        .badge-salida { color: #2b4a7a; font-weight: bold; }
        .footer { margin-top: 18px; text-align: center; color: #8a7a72; font-size: 9px; }
    </style>
</head>
<body onload="window.print()">
    <h1><?= Html::encode($titulo) ?></h1>
    <div class="meta"><?= Html::encode($periodo) ?> · Generado <?= date('d/m/Y H:i') ?></div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Material</th>
                <th>Cantidad</th>
                <th>Edificio</th>
                <th>Receptor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= Html::encode($r['id_movimiento']) ?></td>
                    <td><?= Html::encode($r['fecha_hora']) ?></td>
                    <td class="<?= stripos($r['tipo_movimiento'], 'entrada') !== false ? 'badge-entrada' : 'badge-salida' ?>">
                        <?= Html::encode($r['tipo_movimiento']) ?>
                    </td>
                    <td><?= Html::encode($r['material']) ?></td>
                    <td><?= Html::encode(rtrim(rtrim(number_format((float) $r['cantidad'], 2, '.', ','), '0'), '.')) ?> <?= Html::encode($r['unidad_medida']) ?></td>
                    <td><?= Html::encode($r['edificio'] ?: '—') ?></td>
                    <td><?= Html::encode($r['tipo_personal'] ? ucfirst($r['tipo_personal']) . ' · ' . $r['identificador'] : '—') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">SANITEC · Instituto Tecnológico de Matamoros</div>
</body>
</html>