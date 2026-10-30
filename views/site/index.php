<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Inicio';
?>
<main class="public-home">
    <section class="public-hero">
        <div class="public-hero-content">
            <span class="public-eyebrow">Instituto Tecnológico de Matamoros</span>
            <h1>Bienvenido<?= Yii::$app->user->isGuest ? '' : ', ' . Html::encode(
                Yii::$app->user->identity->nombre ?? '',
            ) ?> a <span>SANITEC</span></h1>
            <p class="public-lead">
                Un espacio para mejorar el cuidado y seguimiento de los servicios sanitarios de nuestra institución.
            </p>
            <p>
                SANITEC es un prototipo inteligente para registrar, supervisar, evaluar y dar seguimiento a los
                servicios sanitarios. Ayuda a detectar y atender oportunamente incidencias relacionadas con la
                limpieza, el abastecimiento de insumos y el mantenimiento.
            </p>

            <?php if (Yii::$app->user->isGuest): ?>
                <div class="public-actions">
                    <?= Html::a(
                        '<i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Iniciar sesión',
                        ['/site/login'],
                        ['class' => 'btn public-button-primary', 'encode' => false],
                    ) ?>
                </div>
                <p class="public-note">
                    ¿Aún no tienes cuenta? <?= Html::a('Regístrate aquí', ['/site/registro']) ?>.
                </p>
            <?php else: ?>
                <div class="public-signed-in">
                    <i class="bi bi-check-circle me-2" aria-hidden="true"></i>
                    Ya iniciaste sesión. Usa el menú para acceder a las opciones disponibles para tu cuenta.
                </div>
            <?php endif; ?>
        </div>
        <div class="public-hero-icon" aria-hidden="true">
            <i class="bi bi-droplet-half"></i>
        </div>
    </section>

    <section class="public-features" aria-label="Funciones de SANITEC">
        <article class="public-feature">
            <span class="public-feature-icon"><i class="bi bi-clipboard2-check" aria-hidden="true"></i></span>
            <h2>Registro y supervisión</h2>
            <p>Organiza los servicios sanitarios y da seguimiento a las tareas de limpieza y atención.</p>
        </article>
        <article class="public-feature">
            <span class="public-feature-icon"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i></span>
            <h2>Evaluación e indicadores</h2>
            <p>Consulta información útil para valorar el servicio y apoyar la gestión administrativa.</p>
        </article>
        <article class="public-feature">
            <span class="public-feature-icon"><i class="bi bi-tools" aria-hidden="true"></i></span>
            <h2>Atención de incidencias</h2>
            <p>Facilita la detección y seguimiento de necesidades de limpieza, insumos y mantenimiento.</p>
        </article>
    </section>
</main>
