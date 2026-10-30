<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Acerca de SANITEC';
$this->params['breadcrumbs'][] = $this->title;
$this->params['meta_description'] = 'Conoce más sobre SANITEC.';
$this->params['meta_keywords'] = 'SANITEC, servicios, limpieza';
?>
<div class="site-about d-flex align-items-center justify-content-center text-center">
    <div class="site-about-content mx-auto">
        <h1 class="display-6 fw-semibold mb-3">This is the About page.</h1>

        <p class="text-body-secondary mb-4">
            You may modify the following file to customize its content:
            <?php if (YII_DEBUG): ?>
                <code class="d-block mt-2"><?= __FILE__ ?></code>
            <?php endif; ?>
        </p>

        <?= Html::a(
            'Go to Homepage',
            Yii::$app->homeUrl,
            ['class' => 'btn btn-outline-primary btn-lg'],
        ) ?>
    </div>
</div>
