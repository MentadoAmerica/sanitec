<?php

use yii\db\Migration;
use yii\db\Query;

class m261114_232444_add_missing_tipos_reporte extends Migration
{
    public function safeUp()
    {
        $nuevos = [
            ['Sanitario fuera de servicio', 'mantenimiento', 'alta'],
            ['Llave o lavabo dañado', 'mantenimiento', 'normal'],
        ];

        foreach ($nuevos as [$nombre, $categoria, $prioridad]) {
            $existe = (new Query())
                ->from('{{%tipos_reporte}}')
                ->where(['nombre' => $nombre])
                ->exists();

            if (!$existe) {
                $this->insert('{{%tipos_reporte}}', [
                    'nombre' => $nombre,
                    'categoria' => $categoria,
                    'prioridad' => $prioridad,
                    'estado' => 1,
                ]);
                echo "  + tipo de reporte: {$nombre}\n";
            }
        }
    }

    public function safeDown()
    {
        echo "add_missing_tipos_reporte no se revierte automáticamente.\n";
        return false;
    }
}