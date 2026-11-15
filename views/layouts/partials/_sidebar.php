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
        [
            'label' => 'Dashboard',
            'url' => ['/site/index'],
            'icon' => 'bi-speedometer2',
        ],
        [
            'label' => 'Operación',
            'icon' => 'bi-clipboard-check',
            'children' => [
                ['label' => 'Reportes e incidencias', 'url' => ['/reportes/index'], 'icon' => 'bi-exclamation-triangle'],
                ['label' => 'Seguimiento de servicios', 'url' => ['/servicios/index'], 'icon' => 'bi-clock-history'],
            ],
        ],
        [
            'label' => 'Servicio Social',
            'icon' => 'bi-mortarboard',
            'children' => [
                ['label' => 'Programar rondas', 'url' => ['/rondas-programadas/index'], 'icon' => 'bi-signpost-split'],
                ['label' => 'Alumnos de servicio social', 'url' => ['/alumnos-servicio-social/index'], 'icon' => 'bi-people'],
                ['label' => 'Turnos de servicio social', 'url' => ['/horarios-servicio-social/index'], 'icon' => 'bi-calendar-week'],
            ],
        ],
        [
            'label' => 'Personal y horarios',
            'icon' => 'bi-person-badge',
            'children' => [
                ['label' => 'Personal de limpieza', 'url' => ['/personal/index'], 'icon' => 'bi-person-badge'],
                ['label' => 'Horarios de intendencia', 'url' => ['/horarios-personal/index'], 'icon' => 'bi-calendar-week'],
            ],
        ],
        [
            'label' => 'Inventario',
            'icon' => 'bi-box-seam',
            'children' => [
                ['label' => 'Materiales', 'url' => ['/materiales/index'], 'icon' => 'bi-box-seam'],
                ['label' => 'Solicitudes de insumos', 'url' => ['/solicitudes-abastecimiento/index'], 'icon' => 'bi-box-arrow-in-down'],
            ],
        ],
        [
            'label' => 'Infraestructura',
            'icon' => 'bi-building',
            'children' => [
                ['label' => 'Edificios', 'url' => ['/edificios/index'], 'icon' => 'bi-building'],
                ['label' => 'Sanitarios', 'url' => ['/sanitarios/index'], 'icon' => 'bi-door-open'],
            ],
        ],
        [
            'label' => 'Configuración',
            'icon' => 'bi-gear',
            'children' => [
                ['label' => 'Usuarios y roles', 'url' => ['/usuarios/index'], 'icon' => 'bi-people'],
                ['label' => 'Catálogo de roles', 'url' => ['/roles/index'], 'icon' => 'bi-list'],
            ],
        ],
        [
            'label' => 'Encuestas',
            'icon' => 'bi-emoji-smile',
            'children' => [
                ['label' => 'Ver respuestas', 'url' => ['/evaluaciones-satisfaccion/index'], 'icon' => 'bi-chat-square-text'],
                ['label' => 'Administrar preguntas', 'url' => ['/evaluaciones-satisfaccion/preguntas'], 'icon' => 'bi-question-circle'],
            ],
        ],
    ];
    break;

      case 2:
    $menuItems = [
        ['label' => 'Seguimiento',         'url' => ['/site/index'],                'icon' => 'bi-speedometer2'],
        ['label' => 'Rondas del día',      'url' => ['/rondas-programadas/index'], 'icon' => 'bi-signpost-split'],
        ['label' => 'Mis verificaciones',  'url' => ['/verificaciones/index'],     'icon' => 'bi-clock-history'],
        ['label' => 'Reportes',            'url' => ['/reportes/index'],           'icon' => 'bi-exclamation-triangle'],
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
                'class' => 'img-fluid',
                'style' => 'max-height: 110px;',
            ]),
            Url::home()
        ) ?>
        <div class="small text-muted mt-2">SANITEC</div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav flex-column px-2">
            <?php foreach ($menuItems as $item):

                // ===== item directo (sin hijos) =====
                if (empty($item['children'])) {
                    $ruta = $item['url'][0] ?? '';
                    $tipoItem = $item['url']['tipo'] ?? null;

                    $partes = explode('/', trim($ruta, '/'));
                    $ctrlItem = $partes[0] ?? '';
                    $accionItem = $partes[1] ?? 'index';

                    $isActive = ($ctrlItem === $currentController && $accionItem === $currentAction);
                    if ($tipoItem !== null) {
                        $isActive = $isActive && ($tipoItem === $currentTipo);
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
                    <?php
                    continue;
                }

                // ===== grupo con hijos =====
                $hijoActivo = false;
                foreach ($item['children'] as $hijo) {
                    $partes = explode('/', trim($hijo['url'][0] ?? '', '/'));
                    $ctrlHijo = $partes[0] ?? '';
                    $accionHijo = $partes[1] ?? 'index';
                    if ($ctrlHijo === $currentController && $accionHijo === $currentAction) {
                        $hijoActivo = true;
                        break;
                    }
                }

                $grupoId = 'grupo-' . strtolower(preg_replace('/[^a-z0-9]+/i', '-', $item['label']));
                ?>
                <li class="nav-item nav-group">
                    <button type="button"
                            class="nav-link sanitec-nav-link nav-group-toggle<?= $hijoActivo ? ' active-group' : '' ?>"
                            data-bs-toggle="collapse"
                            data-bs-target="#<?= $grupoId ?>"
                            aria-expanded="<?= $hijoActivo ? 'true' : 'false' ?>"
                            aria-controls="<?= $grupoId ?>">
                        <i class="bi <?= $item['icon'] ?? 'bi-circle' ?> me-2"></i>
                        <span class="nav-group-label"><?= Html::encode($item['label']) ?></span>
                        <i class="bi bi-chevron-down nav-group-arrow"></i>
                    </button>

                    <div class="collapse<?= $hijoActivo ? ' show' : '' ?>" id="<?= $grupoId ?>">
                        <ul class="nav flex-column nav-group-children">
                            <?php foreach ($item['children'] as $hijo):
                                $partes = explode('/', trim($hijo['url'][0] ?? '', '/'));
                                $ctrlHijo = $partes[0] ?? '';
                                $accionHijo = $partes[1] ?? 'index';
                                $isActive = ($ctrlHijo === $currentController && $accionHijo === $currentAction);
                                ?>
                                <li class="nav-item">
                                    <?= Html::a(
                                        '<i class="bi ' . ($hijo['icon'] ?? 'bi-circle') . ' me-2"></i> ' . Html::encode($hijo['label']),
                                        $hijo['url'],
                                        [
                                            'class' => 'nav-link sanitec-nav-link nav-child-link' . ($isActive ? ' active' : ''),
                                            'encode' => false,
                                        ]
                                    ) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</aside>