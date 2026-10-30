<?php
use yii\helpers\Html;
use yii\helpers\Url;

// Registrar Bootstrap 5, iconos y sanitec.css — pero NO site.css
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');
$this->registerCssFile(Yii::getAlias('@web/css/sanitec.css'));
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" data-bs-theme="light">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> — SANITEC</title>
    <?php $this->head() ?>
</head>
<body class="sanitec-body">
<?php $this->beginBody() ?>

    <header class="sanitec-header">
        <div class="d-flex align-items-center h-100 px-3">
            <a href="<?= Url::home() ?>"
               class="text-decoration-none text-white fw-bold fs-5">
                SANITEC
            </a>
        </div>
    </header>

    <main style="padding-top: var(--sanitec-header-h);">
        <?= $content ?>
    </main>

    <?= $this->render('//layouts/partials/_footer') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>