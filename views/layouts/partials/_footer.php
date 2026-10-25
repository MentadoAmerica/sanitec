<?php
use yii\helpers\Html;
?>
<footer class="sanitec-footer">
    <div class="d-flex justify-content-between align-items-center px-4 py-2 small">
        <div>
            Copyright &copy; <?= date('Y') ?> &mdash;
            <strong>TecNM</strong> / Instituto Tecnológico de Matamoros
        </div>
        <div class="text-muted">
            SANITEC v<?= Html::encode(Yii::$app->version ?? '1.0.0') ?>
        </div>
    </div>
</footer>