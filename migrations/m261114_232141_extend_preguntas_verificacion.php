<?php

use yii\db\Migration;

class m261114_232141_extend_preguntas_verificacion extends Migration
{
    public function safeUp()
    {
        // opciones que ve el alumno: {"1": "Limpio", "2": "Sucio", "3": "Mojado"}
        $this->addColumn(
            '{{%preguntas_verificacion}}',
            'opciones_json',
            $this->text()->null()->after('tipo_respuesta')
        );

        // mapa de triggers: {"2": ["Requiere limpieza"], "3": ["Requiere limpieza", "Fuga de agua"]}
        // la clave es el valor de la respuesta, el valor es un array de nombres de tipos_reporte
        $this->addColumn(
            '{{%preguntas_verificacion}}',
            'respuestas_reporte_json',
            $this->text()->null()->after('opciones_json')
        );

        // orden dentro del grupo (1, 2, 3...)
        $this->addColumn(
            '{{%preguntas_verificacion}}',
            'orden',
            $this->integer()->notNull()->defaultValue(0)->after('respuestas_reporte_json')
        );
    }

    public function safeDown()
    {
        $this->dropColumn('{{%preguntas_verificacion}}', 'orden');
        $this->dropColumn('{{%preguntas_verificacion}}', 'respuestas_reporte_json');
        $this->dropColumn('{{%preguntas_verificacion}}', 'opciones_json');
    }
}