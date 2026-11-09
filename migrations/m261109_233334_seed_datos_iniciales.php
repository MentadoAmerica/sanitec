<?php

use yii\db\Migration;
use yii\db\Query;

class m261109_233334_seed_datos_iniciales extends Migration
{
    public function safeUp()
    {
        $this->seedRoles();
        $this->seedTiposReporte();
        $this->seedMateriales();
        $this->seedEdificios();
        $this->seedSanitarios();
        return true;
    }

    public function safeDown()
    {
        echo "seed_datos_iniciales no se revierte automáticamente para no perder datos en uso.\n";
        return false;
    }

    // ===== roles =====
    private function seedRoles()
    {
        $roles = [
            [1, 'Administrador',      'Configura y supervisa SANITEC.'],
            [2, 'Servicio Social',    'Realiza verificaciones y reportes.'],
            [3, 'Personal operativo', 'Intendentes y personal de limpieza.'],
            [4, 'Almacén',            'Gestiona inventario y entregas.'],
            [5, 'Usuario general',    'Acceso público por QR.'],
        ];

        foreach ($roles as [$id, $nombre, $descripcion]) {
            $existe = (new Query())
                ->from('{{%roles}}')
                ->where(['id_rol' => $id])
                ->exists();

            if (!$existe) {
                $this->insert('{{%roles}}', [
                    'id_rol'      => $id,
                    'nombre'      => $nombre,
                    'descripcion' => $descripcion,
                    'estado'      => 1,
                ]);
                echo "  + rol: {$nombre}\n";
            }
        }
    }

    // ===== tipos_reporte =====
    private function seedTiposReporte()
    {
        $tipos = [
            ['Falta de papel sanitario',   'suministros',    'normal'],
            ['Falta de jabón',             'suministros',    'normal'],
            ['Requiere limpieza',          'limpieza',       'alta'],
            ['Obstrucción del WC',         'mantenimiento',  'alta'],
            ['Fuga de agua',               'mantenimiento',  'urgente'],
            ['Dispensador dañado',         'mantenimiento',  'normal'],
            ['Basurero lleno',             'limpieza',       'normal'],
            ['Mal olor',                   'limpieza',       'alta'],
            ['Puerta o cerrojo dañado',    'mantenimiento',  'normal'],
            ['Luminaria fundida',          'mantenimiento',  'normal'],
            ['Falta de toallas de papel',  'suministros',    'normal'],
            ['Lavabo tapado',              'mantenimiento',  'alta'],
        ];

        foreach ($tipos as [$nombre, $categoria, $prioridad]) {
            $existe = (new Query())
                ->from('{{%tipos_reporte}}')
                ->where(['nombre' => $nombre])
                ->exists();

            if (!$existe) {
                $this->insert('{{%tipos_reporte}}', [
                    'nombre'    => $nombre,
                    'categoria' => $categoria,
                    'prioridad' => $prioridad,
                    'estado'    => 1,
                ]);
                echo "  + tipo de reporte: {$nombre}\n";
            }
        }
    }

    // ===== materiales =====
    private function seedMateriales()
    {
        // formato: codigo, nombre, presentacion, unidad_medida, stock_minimo
        // el costo_unitario queda NULL: se captura al registrar la entrada
        $materiales = [
            ['LIM-001', 'Jabón Líquido para Manos',           'Porrón 20 L',        'litro',     2],
            ['LIM-002', 'Multiusos Fabuloso',                 'Porrón 20 L',        'litro',     2],
            ['LIM-003', 'Cloro / Blanqueador Concentrado',    'Porrón 20 L',        'litro',     2],
            ['LIM-004', 'Limpiavidrios y Espejos',            'Porrón 20 L',        'litro',     1],
            ['LIM-005', 'Ácido Muriático',                    'Porrón 20 L',        'litro',     1],
            ['LIM-006', 'Lustrapiso',                         'Porrón 20 L',        'litro',     2],
            ['LIM-007', 'Pinol',                              'Porrón 20 L',        'litro',     2],
            ['LIM-008', 'Aroma',                              'Porrón 20 L',        'litro',     1],
            ['LIM-009', 'Papel Higiénico Jumbo',              'Caja con 6 piezas',  'pieza',     3],
            ['LIM-010', 'Papel Higiénico Jr',                 'Caja con 12 piezas', 'pieza',     3],
            ['LIM-011', 'Papel Café para Manos',              'Caja con 6 piezas',  'pieza',     3],
            ['LIM-012', 'Jabón en Polvo',                     'Bolsa 9 kg',         'kilogramo', 2],
            ['LIM-013', 'Bolsas para Basura Chica (0.7x0.90)', 'Bolsa 25 piezas',   'pieza',     5],
            ['LIM-014', 'Bolsas para Basura Grande (0.9x1.20)','Bolsa 25 piezas',   'pieza',     5],
            ['LIM-015', 'Tapetes para Mingitorio',            'Pieza',              'pieza',     2],
            ['LIM-016', 'Escoba de Paja',                     'Pieza',              'pieza',     2],
            ['LIM-017', 'Escoba de Vinilo',                   'Pieza',              'pieza',     2],
            ['LIM-018', 'Trapeador',                          'Pieza',              'pieza',     3],
        ];

        foreach ($materiales as [$codigo, $nombre, $presentacion, $unidad, $minimo]) {
            $existe = (new Query())
                ->from('{{%materiales}}')
                ->where(['nombre' => $nombre])
                ->exists();

            if (!$existe) {
                $this->insert('{{%materiales}}', [
                    'nombre'         => $nombre,
                    'descripcion'    => $codigo . ' · ' . $presentacion,
                    'unidad_medida'  => $unidad,
                    'stock_actual'   => 0,
                    'stock_minimo'   => $minimo,
                    'costo_unitario' => null,
                    'estado'         => 1,
                ]);
                echo "  + material: {$codigo} {$nombre}\n";
            }
        }
    }

    // ===== edificios =====
    private function seedEdificios()
    {
        $edificios = [
            ['J',         'Aulas',                                        'Zona Centro'],
            ['R1',        'Módulo Académico de Ciencias Básicas - Aulas', 'Zona Centro'],
            ['R2',        'Módulo Académico de Ciencias Básicas - Aulas', 'Zona Centro'],
            ['Y',         'Módulo Académico Posgrado - Aulas',            'Zona Sur'],
            ['Gimnasio',  'Auditorio',                                    'Zona Este'],
            ['S',         'Laboratorio de Cómputo',                       'Zona Oeste'],
            ['Q',         'Aulas',                                        'Zona Sur'],
            ['Biblioteca','Centro de Información',                        'Centro'],
            ['A',         'Administrativo - Dirección',                   'Zona Este'],
        ];

        foreach ($edificios as [$nombre, $descripcion, $ubicacion]) {
            $existe = (new Query())
                ->from('{{%edificios}}')
                ->where(['nombre' => $nombre])
                ->exists();

            if (!$existe) {
                $this->insert('{{%edificios}}', [
                    'nombre'      => $nombre,
                    'descripcion' => $descripcion,
                    'ubicacion'   => $ubicacion,
                    'estado'      => 1,
                ]);
                echo "  + edificio: {$nombre}\n";
            }
        }
    }

    // ===== sanitarios =====
    private function seedSanitarios()
    {
        // dos por edificio (hombres/mujeres) en planta baja.
        // el módulo R (R1 + R2) comparte los 2 de planta baja del R1.
        // R2 no tiene sanitarios propios.
        $mapa = [
            ['J',          'J-01',  'Hombres', 'Planta baja'],
            ['J',          'J-02',  'Mujeres', 'Planta baja'],
            ['R1',         'R1-01', 'Hombres', 'Planta baja'],
            ['R1',         'R1-02', 'Mujeres', 'Planta baja'],
            ['Y',          'Y-01',  'Hombres', 'Planta baja'],
            ['Y',          'Y-02',  'Mujeres', 'Planta baja'],
            ['Gimnasio',   'GIM-01','Hombres', 'Planta baja'],
            ['Gimnasio',   'GIM-02','Mujeres', 'Planta baja'],
            ['S',          'S-01',  'Hombres', 'Planta baja'],
            ['S',          'S-02',  'Mujeres', 'Planta baja'],
            ['Q',          'Q-01',  'Hombres', 'Planta baja'],
            ['Q',          'Q-02',  'Mujeres', 'Planta baja'],
            ['Biblioteca', 'BIB-01','Hombres', 'Planta baja'],
            ['Biblioteca', 'BIB-02','Mujeres', 'Planta baja'],
            ['A',          'A-01',  'Hombres', 'Planta baja'],
            ['A',          'A-02',  'Mujeres', 'Planta baja'],
        ];

        foreach ($mapa as [$edificioNombre, $sanitarioNombre, $tipo, $ubicacion]) {
            $idEdificio = (new Query())
                ->select('id_edificio')
                ->from('{{%edificios}}')
                ->where(['nombre' => $edificioNombre])
                ->scalar();

            if (!$idEdificio) {
                echo "  ! edificio {$edificioNombre} no encontrado, se omite {$sanitarioNombre}\n";
                continue;
            }

            $existe = (new Query())
                ->from('{{%sanitarios}}')
                ->where(['nombre' => $sanitarioNombre, 'id_edificio' => $idEdificio])
                ->exists();

            if (!$existe) {
                $this->insert('{{%sanitarios}}', [
                    'id_edificio' => $idEdificio,
                    'nombre'      => $sanitarioNombre,
                    'tipo'        => $tipo,
                    'ubicacion'   => $ubicacion,
                    'codigo_qr'   => $this->generarTokenQr($sanitarioNombre),
                    'estado'      => 1,
                ]);
                echo "  + sanitario: {$sanitarioNombre} ({$edificioNombre})\n";
            }
        }
    }

    private function generarTokenQr(string $semilla): string
    {
        return 'SAN-' . strtoupper(substr(hash('sha256', $semilla . microtime(true) . random_int(1000, 9999)), 0, 12));
    }
}