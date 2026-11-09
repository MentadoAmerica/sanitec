<?php

use yii\db\Migration;

class m261109_000001_add_turno_and_satisfaction_questions extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%evaluaciones_satisfaccion}}', 'turno', $this->string(30)->notNull()->defaultValue('fuera_de_horario'));

        $this->delete('{{%preguntas_satisfaccion}}', ['>','id_pregunta',0]);

        $preguntas = [
            [1, '¿Cómo calificas el nivel general de limpieza del sanitario?', 'calificacion'],
            [2, '¿Cómo calificas la higiene de las superficies (lavabos, pisos y sanitarios)?', 'calificacion'],
            [3, '¿Cómo calificas el control de malos olores dentro del sanitario?', 'calificacion'],
            [4, '¿Cómo calificas la disponibilidad de papel higiénico?', 'calificacion'],
            [5, '¿Cómo calificas la disponibilidad de jabón para manos?', 'calificacion'],
            [6, '¿Cómo calificas la disponibilidad de agua corriente en los lavabos?', 'calificacion'],
            [7, '¿Cómo calificas la disponibilidad de toallas de papel o el funcionamiento del secador de manos?', 'calificacion'],
            [8, '¿Cómo calificas la disponibilidad y capacidad de los botes de basura?', 'calificacion'],
            [9, '¿Cómo calificas el funcionamiento de las llaves y grifos?', 'calificacion'],
            [10, '¿Cómo calificas el funcionamiento de los inodoros o mingitorios?', 'calificacion'],
            [11, '¿Cómo calificas el estado de las puertas, cerrojos, pestillos y seguros?', 'calificacion'],
            [12, '¿Cómo calificas el funcionamiento de la iluminación general?', 'calificacion'],
            [13, '¿Cómo calificas el estado del piso respecto a fugas o acumulación de agua?', 'calificacion'],
            [14, '¿Con qué frecuencia utilizas estas instalaciones sanitarias?', 'frecuencia'],
            [15, '¿Qué aspecto consideras que necesita mayor atención o mantenimiento?', 'mantenimiento'],
        ];

        foreach ($preguntas as $pregunta) {
            $this->insert('{{%preguntas_satisfaccion}}', [
                'id_pregunta' => $pregunta[0],
                'pregunta' => $pregunta[1],
                'categoria' => $pregunta[2],
                'estado' => 1,
            ]);
        }

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('{{%evaluaciones_satisfaccion}}', 'turno');
        $this->delete('{{%preguntas_satisfaccion}}');
        return true;
    }
}
