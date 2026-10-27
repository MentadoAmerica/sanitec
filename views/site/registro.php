<?php

/** @var yii\web\View $this */
/** @var app\models\RegistroForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\widgets\Alert;

$this->title = 'Crear cuenta';
?>
<div class="site-login d-flex align-items-center justify-content-center py-5">
    <div class="card border-0 overflow-hidden login-split-card">
        <div class="row g-0">
            <div class="col-md-5 d-none d-md-flex login-brand-panel">
                <div class="registro-brand-content d-flex flex-column align-items-center justify-content-center p-4 p-lg-4 w-100 text-center">
                    <?= Html::img(
                        Yii::getAlias('@web/images/Gorila.png'),
                        [
                            'alt' => 'Mascota de SANITEC',
                            'class' => 'registro-brand-logo bg-white rounded-4 p-3 mb-4',
                            'height' => 240,
                            'width' => 240,
                        ],
                    ) ?>
                    <div>
                        <h2 class="fw-bold mb-3 registro-brand-title">Únete a SANITEC</h2>
                        <p class="login-brand-text mb-0">
                            Crea tu cuenta para acceder a las opciones disponibles para usuarios generales.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="p-4 p-lg-5">
                    <div class="d-md-none text-center mb-3">
                        <?= Html::img(
                            Yii::getAlias('@web/images/Gorila.png'),
                            [
                                'alt' => 'Mascota de SANITEC',
                                'class' => 'registro-mobile-logo',
                                'height' => 80,
                                'width' => 80,
                            ],
                        ) ?>
                    </div>
                    <div class="text-center mb-4">
                        <h1 class="h3 fw-bold mb-1"><?= Html::encode($this->title) ?></h1>
                        <p class="text-body-secondary small mb-0">Llena el formulario con tus datos para crear tu cuenta.</p>
                    </div>

                    <?= Alert::widget() ?>

                    <?php $form = ActiveForm::begin(['id' => 'registro-form']); ?>

                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'nombre')->textInput([
                                'maxlength' => true,
                                'autofocus' => true,
                                'autocomplete' => 'given-name',
                            ]) ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'apellidos')->textInput([
                                'maxlength' => true,
                                'autocomplete' => 'family-name',
                            ]) ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'correo')->input('email', [
                        'maxlength' => true,
                        'placeholder' => 'tu-correo@itmatamoros.edu.mx',
                        'autocomplete' => 'email',
                    ]) ?>

                    <?= $form->field($model, 'telefono')->textInput([
                        'maxlength' => true,
                        'autocomplete' => 'tel',
                    ]) ?>

                    <?= $form->field($model, 'contrasena')->passwordInput([
                        'minlength' => 8,
                        'autocomplete' => 'new-password',
                    ])->hint('Debe tener al menos 8 caracteres.') ?>

                    <?= $form->field($model, 'confirmarContrasena')->passwordInput([
                        'minlength' => 8,
                        'autocomplete' => 'new-password',
                    ]) ?>

                    <div class="d-grid mt-4">
                        <?= Html::submitButton(
                            '<i class="bi bi-person-check me-2" aria-hidden="true"></i>Crear cuenta',
                            ['class' => 'btn login-btn btn-lg rounded-3 text-white', 'encode' => false],
                        ) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                    <div class="text-body-secondary text-center mt-4 small">
                        ¿Ya tienes cuenta? <?= Html::a('Inicia sesión', ['/site/login'], ['class' => 'fw-semibold']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
