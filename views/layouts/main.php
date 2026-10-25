<?php
use yii\helpers\Html;
use app\widgets\Alert;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> — SANITEC</title>

    <!-- Bootstrap Icons (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS propio de SANITEC -->
    <link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/css/sanitec.css">

    <?php $this->head() ?>
</head>
<body class="sanitec-body">
<?php $this->beginBody() ?>

    <?= $this->render('//layouts/partials/_header') ?>
    <?= $this->render('//layouts/partials/_sidebar') ?>

    <div id="sanitec-main" class="sanitec-main">
        <div class="container-fluid py-4">

            <?php if (isset($this->params['breadcrumbs'])): ?>
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <?php foreach ($this->params['breadcrumbs'] as $i => $crumb): ?>
                            <?php if (is_array($crumb)): ?>
                                <li class="breadcrumb-item">
                                    <?= Html::a($crumb['label'], $crumb['url']) ?>
                                </li>
                            <?php else: ?>
                                <li class="breadcrumb-item active">
                                    <?= Html::encode($crumb) ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </nav>
            <?php endif; ?>

            <?= Alert::widget() ?>
            <?= $content ?>
        </div>

        <?= $this->render('//layouts/partials/_footer') ?>
    </div>

    <!-- Bootstrap 5 JS (necesario para dropdowns, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toggle del sidebar -->
    <script>
        (function () {
            const body   = document.body;
            const btn    = document.getElementById('btn-toggle-sidebar');
            const main   = document.getElementById('sanitec-main');
            const sidebar= document.getElementById('sanitec-sidebar');
            if (!btn || !sidebar) return;

            // Estado inicial en desktop: abierto. En móvil: cerrado.
            if (window.innerWidth < 992) body.classList.add('sidebar-collapsed');

            btn.addEventListener('click', function () {
                body.classList.toggle('sidebar-collapsed');
            });
        })();
    </script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>