<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * devuelve los items del menu segun el estado de sesion y el rol.
 * roles (id_rol):
 *   1 = administrador
 *   2 = servicio social
 *   3 = personal operativo (intendentes)
 *   4 = almacen
 *   5 = usuario general
 */
$menuItems = [];

if (Yii::$app->user->isGuest) {
    $menuItems = [
        ['label' => 'Inicio', 'url' => ['/site/index'], 'icon' => 'bi-house-door'],
        ['label' => 'Entrar', 'url' => ['/site/login'], 'icon' => 'bi-person'],
    ];
} else {
    $rol = (int) (Yii::$app->user->identity->id_rol ?? 0);

    switch ($rol) {
        case 1: // administrador
            $menuItems = [
                ['label' => 'Dashboard',                'url' => ['/site/index'],                          'icon' => 'bi-speedometer2'],
                ['label' => 'Usuarios y roles',         'url' => ['/usuarios/index'],                     'icon' => 'bi-people'],
                ['label' => 'Edificios',                'url' => ['/edificios/index'],                    'icon' => 'bi-building'],
                ['label' => 'Sanitarios',               'url' => ['/sanitarios/index'],                   'icon' => 'bi-building'],
                ['label' => 'Personal de limpieza',     'url' => ['/personal/index'],                     'icon' => 'bi-person-badge'],
                ['label' => 'Horarios y asignaciones',  'url' => ['/horarios-personal/index'],            'icon' => 'bi-calendar-week'],
                ['label' => 'Horarios servicio social','url' => ['/horarios-servicio-social/index'],     'icon' => 'bi-calendar-week'],
                ['label' => 'Programar rondas',         'url' => ['/rondas-programadas/index'],           'icon' => 'bi-signpost-split'],
                ['label' => 'Seguimiento de servicios', 'url' => ['/servicios/index'],                    'icon' => 'bi-clipboard-check'],
                ['label' => 'Reportes e incidencias',   'url' => ['/reportes/index'],                     'icon' => 'bi-exclamation-triangle'],
                ['label' => 'Solicitudes de insumos',   'url' => ['/solicitudes-abastecimiento/index'],   'icon' => 'bi-box-arrow-in-down'],
                ['label' => 'Inventario',               'url' => ['/materiales/index'],                   'icon' => 'bi-box-seam'],
                ['label' => 'Servicio Social',          'url' => ['/alumno-horarios-ss/index'],           'icon' => 'bi-mortarboard'],
                ['label' => 'Encuestas',                'url' => ['/evaluaciones-satisfaccion/index'],    'icon' => 'bi-emoji-smile'],
                ['label' => 'Catálogos',                'url' => ['/roles/index'],                        'icon' => 'bi-gear'],
            ];
            break;

        case 2: // servicio social
            $menuItems = [
                ['label' => 'Mis actividades', 'url' => ['/site/index'],                 'icon' => 'bi-speedometer2'],
                ['label' => 'Mis rondas',      'url' => ['/rondas-programadas/index'],   'icon' => 'bi-signpost-split'],
                ['label' => 'Historial',       'url' => ['/verificaciones/index'],       'icon' => 'bi-clock-history'],
                ['label' => 'Reportes',        'url' => ['/reportes/index'],             'icon' => 'bi-exclamation-triangle'],
                ['label' => 'Solicitar insumos','url' => ['/solicitudes-abastecimiento/index'], 'icon' => 'bi-box-arrow-in-down'],
            ];
            break;

        case 3: // intendente
            $menuItems = [
                ['label' => 'Inicio',    'url' => ['/site/index'],                'icon' => 'bi-speedometer2'],
                ['label' => 'Reportes',  'url' => ['/reportes/index'],            'icon' => 'bi-exclamation-triangle'],
                ['label' => 'Historial', 'url' => ['/reportes/index'],            'icon' => 'bi-clock-history'],
            ];
            break;

        case 4: // almacen
            $menuItems = [
                ['label' => 'Inicio',                 'url' => ['/site/index'],                                         'icon' => 'bi-speedometer2'],
                ['label' => 'Catálogo de materiales', 'url' => ['/materiales/index'],                                 'icon' => 'bi-box-seam'],
                ['label' => 'Entradas',               'url' => ['/movimientos-inventario/create', 'tipo' => 'entrada'], 'icon' => 'bi-box-arrow-in-down'],
                ['label' => 'Salidas y entregas',     'url' => ['/movimientos-inventario/create', 'tipo' => 'salida'],  'icon' => 'bi-box-arrow-up'],
                ['label' => 'Solicitudes de insumos','url' => ['/solicitudes-abastecimiento/index'],                    'icon' => 'bi-clipboard-check'],
                ['label' => 'Historial',              'url' => ['/movimientos-inventario/index'],                       'icon' => 'bi-clock-history'],
            ];
            break;

        default:
            $menuItems = [
                ['label' => 'Inicio', 'url' => ['/site/index'], 'icon' => 'bi-house-door'],
            ];
    }
}

// contexto actual para decidir que item esta activo
$currentController = Yii::$app->controller->id;
$currentAction     = Yii::$app->controller->action->id ?? 'index';
$currentTipo       = Yii::$app->request->get('tipo');
?>

<aside id="sanitec-sidebar" class="sanitec-sidebar">
    <div class="sidebar-logo text-center py-3">
        <?= Html::a(
            Html::img('@web/images/logo-tec.jpg', [
                'alt' => 'TecNM Matamoros',
                'class' => 'img-fluid sidebar-brand-logo',
            ]),
            Url::home()
        ) ?>
        <div class="sidebar-brand-name">SANITEC</div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav flex-column px-2">
            <?php foreach ($menuItems as $item):
                $ruta     = $item['url'][0] ?? '';
                $tipoItem = $item['url']['tipo'] ?? null;

                $partes     = explode('/', trim($ruta, '/'));
                $ctrlItem   = $partes[0] ?? '';
                $accionItem = $partes[1] ?? 'index';

                // coincide controlador + accion
                $isActive = ($ctrlItem === $currentController && $accionItem === $currentAction);

                // si el item tiene tipo, tambien debe coincidir
                if ($tipoItem !== null) {
                    $isActive = $isActive && ($tipoItem === $currentTipo);
                } elseif ($accionItem === 'create' && $currentTipo !== null) {
                    // item que apunta a create sin tipo, no marcar si la url trae tipo
                    $isActive = false;
                }
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