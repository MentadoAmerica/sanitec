<?php

use yii\db\Migration;
use yii\db\Query;

class m261114_232245_seed_preguntas_verificacion extends Migration
{
    public function safeUp()
    {
        // la columna 'categoria' se usa como grupo ui (tab a, b, c)
        // el campo 'respuestas_reporte_json' mapea valor_respuesta -> [nombres de tipos_reporte]
        // si la respuesta no esta en el mapa, no genera reporte automatico
        $preguntas = [
            // ===== grupo a: limpieza y condiciones generales =====
            [
                'categoria' => 'limpieza',
                'orden' => 1,
                'pregunta' => '¿El piso del sanitario se encuentra limpio?',
                'opciones' => ['1' => 'Limpio', '2' => 'Sucio', '3' => 'Mojado o resbaloso'],
                'triggers' => [
                    '2' => ['Requiere limpieza'],
                    '3' => ['Requiere limpieza'],
                ],
            ],
            [
                'categoria' => 'limpieza',
                'orden' => 2,
                'pregunta' => '¿Los inodoros y mingitorios se encuentran limpios?',
                'opciones' => ['1' => 'Limpios', '2' => 'Sucios', '3' => 'Fuera de servicio'],
                'triggers' => [
                    '2' => ['Requiere limpieza'],
                    '3' => ['Sanitario fuera de servicio'],
                ],
            ],
            [
                'categoria' => 'limpieza',
                'orden' => 3,
                'pregunta' => '¿Los lavabos y espejos se encuentran limpios?',
                'opciones' => ['1' => 'Sí', '2' => 'No'],
                'triggers' => ['2' => ['Requiere limpieza']],
            ],
            [
                'categoria' => 'limpieza',
                'orden' => 4,
                'pregunta' => '¿El sanitario presenta malos olores?',
                'opciones' => ['1' => 'No', '2' => 'Sí, olor moderado', '3' => 'Sí, olor fuerte'],
                'triggers' => [
                    '2' => ['Mal olor'],
                    '3' => ['Mal olor'],
                ],
            ],
            [
                'categoria' => 'limpieza',
                'orden' => 5,
                'pregunta' => '¿Los botes de basura se encuentran en condiciones adecuadas?',
                'opciones' => ['1' => 'Vacíos o con capacidad disponible', '2' => 'Llenos', '3' => 'Desbordados'],
                'triggers' => [
                    '2' => ['Basurero lleno'],
                    '3' => ['Basurero lleno'],
                ],
            ],

            // ===== grupo b: abastecimiento =====
            [
                'categoria' => 'abastecimiento',
                'orden' => 6,
                'pregunta' => '¿Hay papel higiénico disponible?',
                'opciones' => ['1' => 'Sí', '2' => 'No', '3' => 'No aplica'],
                'triggers' => ['2' => ['Falta de papel sanitario']],
            ],
            [
                'categoria' => 'abastecimiento',
                'orden' => 7,
                'pregunta' => '¿Hay jabón disponible para el lavado de manos?',
                'opciones' => ['1' => 'Sí', '2' => 'No', '3' => 'No aplica'],
                'triggers' => ['2' => ['Falta de jabón']],
            ],
            [
                'categoria' => 'abastecimiento',
                'orden' => 8,
                'pregunta' => '¿Hay toallas de papel o algún medio para secarse las manos?',
                'opciones' => ['1' => 'Sí', '2' => 'No', '3' => 'No aplica'],
                'triggers' => ['2' => ['Falta de toallas de papel']],
            ],
            [
                'categoria' => 'abastecimiento',
                'orden' => 9,
                'pregunta' => '¿Los dispensadores de materiales funcionan correctamente?',
                'opciones' => ['1' => 'Sí', '2' => 'No', '3' => 'No aplica'],
                'triggers' => ['2' => ['Dispensador dañado']],
            ],

            // ===== grupo c: funcionamiento y mantenimiento =====
            [
                'categoria' => 'mantenimiento',
                'orden' => 10,
                'pregunta' => '¿Los inodoros y mingitorios funcionan correctamente?',
                'opciones' => ['1' => 'Sí', '2' => 'No', '3' => 'No aplica'],
                'triggers' => ['2' => ['Sanitario fuera de servicio']],
            ],
            [
                'categoria' => 'mantenimiento',
                'orden' => 11,
                'pregunta' => '¿Los lavabos y las llaves de agua funcionan correctamente?',
                'opciones' => ['1' => 'Sí', '2' => 'No', '3' => 'No aplica'],
                'triggers' => ['2' => ['Llave o lavabo dañado']],
            ],
            [
                'categoria' => 'mantenimiento',
                'orden' => 12,
                'pregunta' => '¿Se observan fugas de agua en tuberías, llaves o sanitarios?',
                'opciones' => ['1' => 'No', '2' => 'Sí'],
                'triggers' => ['2' => ['Fuga de agua']],
            ],
            [
                'categoria' => 'mantenimiento',
                'orden' => 13,
                'pregunta' => '¿Se observan obstrucciones en inodoros, mingitorios o desagües?',
                'opciones' => ['1' => 'No', '2' => 'Sí'],
                'triggers' => ['2' => ['Obstrucción del WC']],
            ],
            [
                'categoria' => 'mantenimiento',
                'orden' => 14,
                'pregunta' => '¿Las puertas, cerraduras y accesorios se encuentran en buen estado?',
                'opciones' => ['1' => 'Sí', '2' => 'No', '3' => 'No aplica'],
                'triggers' => ['2' => ['Puerta o cerrojo dañado']],
            ],
            [
                'categoria' => 'mantenimiento',
                'orden' => 15,
                'pregunta' => '¿Existe algún otro daño o situación que requiera mantenimiento?',
                'opciones' => ['1' => 'No', '2' => 'Sí'],
                'triggers' => [], // sin trigger: el alumno crea reporte manual si lo necesita
            ],
        ];

        foreach ($preguntas as $p) {
            $existe = (new Query())
                ->from('{{%preguntas_verificacion}}')
                ->where(['pregunta' => $p['pregunta']])
                ->exists();

            if ($existe) {
                continue;
            }

            $this->insert('{{%preguntas_verificacion}}', [
                'pregunta' => $p['pregunta'],
                'categoria' => $p['categoria'],
                'tipo_respuesta' => null,
                'opciones_json' => json_encode($p['opciones'], JSON_UNESCAPED_UNICODE),
                'respuestas_reporte_json' => json_encode($p['triggers'], JSON_UNESCAPED_UNICODE),
                'orden' => $p['orden'],
                'obligatoria' => 1,
                'estado' => 1,
            ]);
            echo "  + pregunta {$p['orden']}: {$p['pregunta']}\n";
        }
    }

    public function safeDown()
    {
        echo "seed_preguntas_verificacion no se revierte automáticamente.\n";
        return false;
    }
}