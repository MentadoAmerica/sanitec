<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Devuelve los ítems del menú según el estado de sesión y el rol.
 * Los "roles" se corresponden con la tabla `roles` (id_rol):
 *   1 = Administrador
 *   2 = Servicio Social
 *   3 = Personal operativo (intendentes — no usan web, pero por si acaso)
 *   4 = Almacén
 *   5 = Usuario general (acceso público por QR)
 */
$menuItems = [];

if (Yii::$app->user->isGuest) {
    // ===== MENÚ PÚBLICO (sin sesión) =====
    $menuItems = [
        ['label' => 'Inicio',           'url' => ['/site/index'],   'icon' => 'bi-house-door'],
        ['label' => 'Entrar',            'url' => ['/site/login'],   'icon' => 'bi-person'],
        ['label' => 'Comprobar Docs.',   'url' => ['/site/comprobar-docs'], 'icon' => 'bi-check2-square'],
        ['label' => 'Revista',           'url' => ['/site/revista'], 'icon' => 'bi-list-ul'],
    ];
} else {
    // ===== MENÚ SEGÚN ROL =====
    $rol = (int) (Yii::$app->user->identity->id_rol ?? 0);

    switch ($rol) {
        case 1: // Administrador
            $menuItems = [
                ['label' => 'Dashboard',            'url' => ['/site/index'],         'icon' => 'bi-speedometer2'],
                ['label' => 'Usuarios y roles',     'url' => ['/usuarios/index'],     'icon' => 'bi-people'],
                ['label' => 'Edificios y sanitarios','url' => ['/edificios/index'],  'icon' => 'bi-building'],
                ['label' => 'Personal de limpieza', 'url' => ['/personal/index'],     'icon' => 'bi-person-badge'],
                ['label' => 'Horarios y asignaciones','url' => ['/horarios-personal/index'], 'icon' => 'bi-calendar-week'],
                ['label' => 'Programación de rondas','url' => ['/rondas-programadas/index'],'icon' => 'bi-signpost-split'],
                ['label' => 'Seguimiento de servicios','url' => ['/servicios/index'],  'icon' => 'bi-clipboard-check'],
                ['label' => 'Reportes e incidencias','url' => ['/reportes/index'],    'icon' => 'bi-exclamation-triangle'],
                ['label' => 'Atención de incidencias','url' => ['/atenciones-reportes/index'], 'icon' => 'bi-wrench'],
                ['label' => 'Inventario',           'url' => ['/materiales/index'],   'icon' => 'bi-box-seam'],
                ['label' => 'Servicio Social',      'url' => ['/alumnos-servicio-social/index'], 'icon' => 'bi-mortarboard'],
                ['label' => 'Encuestas',            'url' => ['/evaluaciones-satisfaccion/index'], 'icon' => 'bi-emoji-smile'],
                ['label' => 'Catálogos',            'url' => ['/roles/index'],        'icon' => 'bi-gear'],
            ];
            break;

        case 2: // Servicio Social
            $menuItems = [
                ['label' => 'Mis actividades',      'url' => ['/site/index'],                    'icon' => 'bi-speedometer2'],
                ['label' => 'Rondas programadas',   'url' => ['/rondas-programadas/index'],      'icon' => 'bi-signpost-split'],
                ['label' => 'Verificación',         'url' => ['/verificaciones/create'],         'icon' => 'bi-clipboard-check'],
                ['label' => 'Historial',            'url' => ['/verificaciones/index'],          'icon' => 'bi-clock-history'],
                ['label' => 'Mis reportes',         'url' => ['/reportes/index'],                'icon' => 'bi-exclamation-triangle'],
                ['label' => 'Mi horario',           'url' => ['/alumnos-servicio-social/view'],  'icon' => 'bi-calendar'],
            ];
            break;
        
        case 3: // Personal operativo / Intendente
            $menuItems = [
                ['label' => 'Avisos de servicio',  'url' => ['/notificaciones/index'], 'icon' => 'bi-bell'],
                ['label' => 'Mis registros',       'url' => ['/servicios/index'],      'icon' => 'bi-clock-history'],
            ];
            break;

        case 4: // Almacén
            $menuItems = [
                ['label' => 'Dashboard almacén',    'url' => ['/site/index'],                    'icon' => 'bi-speedometer2'],
                ['label' => 'Catálogo de materiales','url' => ['/materiales/index'],             'icon' => 'bi-box-seam'],
                ['label' => 'Entradas',             'url' => ['/movimientos-inventario/create'], 'icon' => 'bi-box-arrow-in-down'],
                ['label' => 'Salidas y entregas',   'url' => ['/movimientos-inventario/create'], 'icon' => 'bi-box-arrow-up'],
                ['label' => 'Historial',            'url' => ['/movimientos-inventario/index'],  'icon' => 'bi-clock-history'],
            ];
            break;

        default:
            // Cualquier otro rol no contemplado → menú público mínimo
            $menuItems = [
                ['label' => 'Inicio', 'url' => ['/site/index'], 'icon' => 'bi-house-door'],
            ];
    }
}

// Detectar el controlador actual para marcar el activo
$currentController = Yii::$app->controller->id;
?>

<aside id="sanitec-sidebar" class="sanitec-sidebar">
    <!-- Logo institucional -->
    <div class="sidebar-logo text-center py-3">
        <?= Html::a(
            Html::img('@web/images/logo-tec.jpg', [
                'alt' => 'TecNM Matamoros',
                'class' => 'img-fluid',
                'style' => 'max-height: 110px;',
            ]),
            Url::home()
        ) ?>
        <div class="small text-muted mt-2">SANITEC</div>
    </div>

    <!-- Navegación -->
    <nav class="sidebar-nav">
        <ul class="nav flex-column px-2">
            <?php foreach ($menuItems as $item):
                $url = $item['url'][0] ?? '';
                // Marca como activo si la URL contiene el controlador actual
                $isActive = strpos($url, '/' . $currentController . '/') !== false
                            || ($currentController === 'site' && $url === '/site/index');
            ?>
                <li class="nav-item">
                    <?= Html::a(
                        '<i class="bi ' . ($item['icon'] ?? 'bi-circle') . ' me-2"></i> ' . Html::encode($item['label']),
                        $item['url'],
                        [
                            'class' => 'nav-link sanitec-nav-link' . ($isActive ? ' active' : ''),
                            'encode' => false,
                        ]
                    ) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</aside>