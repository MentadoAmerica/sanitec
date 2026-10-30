<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<header class="sanitec-header">
    <div class="d-flex align-items-center h-100 px-3">

        <!-- Botón hamburguesa -->
        <button id="btn-toggle-sidebar" class="btn btn-link text-white p-0 me-3"
                type="button" aria-label="Mostrar/ocultar menú" aria-controls="sanitec-sidebar" aria-expanded="false">
            <i class="bi bi-list fs-3"></i>
        </button>

        <!-- Marca -->
        <a href="<?= Url::home() ?>" class="text-decoration-none text-white fw-bold fs-5">
            SANITEC
        </a>

        <!-- Zona derecha: usuario / login -->
        <div class="ms-auto d-flex align-items-center gap-3">
            <?php if (!Yii::$app->user->isGuest): ?>
                <span class="text-white-50 small">
                    <?= Html::encode(Yii::$app->user->identity->nombre ?? 'Usuario') ?>
                </span>
                <?= Html::a(
                    '<i class="bi bi-box-arrow-right"></i> Salir',
                    ['/site/logout'],
                    [
                        'class' => 'btn btn-sm btn-outline-light',
                        'data-method' => 'post',
                    ]
                ) ?>
            <?php else: ?>
                <?= Html::a(
                    '<i class="bi bi-box-arrow-in-right"></i> Entrar',
                    ['/site/login'],
                    ['class' => 'btn btn-sm btn-outline-light']
                ) ?>
            <?php endif; ?>
        </div>

    </div>
</header>