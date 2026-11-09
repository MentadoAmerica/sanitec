(function () {
    'use strict';

    document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
        const input = button.parentElement.querySelector('input');
        if (input) {
            input.type = 'password';
        }

        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.add('bi-eye-slash');
            icon.classList.remove('bi-eye');
        }
        button.setAttribute('aria-pressed', 'false');
        button.setAttribute('aria-label', 'Mostrar contraseña');
        button.title = 'Mostrar contraseña';
    });

    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-password-toggle]');
        if (!button) {
            return;
        }

        const input = button.parentElement.querySelector('input');
        if (!input) {
            return;
        }

        const isVisible = input.type === 'text';
        input.type = isVisible ? 'password' : 'text';
        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.toggle('bi-eye', !isVisible);
            icon.classList.toggle('bi-eye-slash', isVisible);
        }
        button.setAttribute('aria-pressed', String(!isVisible));
        button.setAttribute('aria-label', isVisible ? 'Mostrar contraseña' : 'Ocultar contraseña');
        button.title = isVisible ? 'Mostrar contraseña' : 'Ocultar contraseña';
    });
})();
