<?php

use yii\db\Migration;

class m261109_000002_add_survey_question_options extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%preguntas_satisfaccion}}', 'opciones_json', $this->text()->null());
        $this->addColumn('{{%preguntas_satisfaccion}}', 'escala_min', $this->tinyInteger()->unsigned()->notNull()->defaultValue(1));
        $this->addColumn('{{%preguntas_satisfaccion}}', 'escala_max', $this->tinyInteger()->unsigned()->notNull()->defaultValue(5));

        $optionsByQuestion = [
            1 => ['Muy insatisfecho / Deficiente', 'Insatisfecho', 'Neutral / Regular', 'Satisfecho', 'Muy satisfecho / Excelente'],
            2 => ['Muy insatisfecho / Deficiente', 'Insatisfecho', 'Neutral / Regular', 'Satisfecho', 'Muy satisfecho / Excelente'],
            3 => ['Muy insatisfecho / Deficiente', 'Insatisfecho', 'Neutral / Regular', 'Satisfecho', 'Muy satisfecho / Excelente'],
            4 => ['Muy insatisfecho / Deficiente', 'Insatisfecho', 'Neutral / Regular', 'Satisfecho', 'Muy satisfecho / Excelente'],
            5 => ['Muy insatisfecho / Deficiente', 'Insatisfecho', 'Neutral / Regular', 'Satisfecho', 'Muy satisfecho / Excelente'],
            6 => ['Muy insatisfecho / Deficiente', 'Insatisfecho', 'Neutral / Regular', 'Satisfecho', 'Muy satisfecho / Excelente'],
            7 => ['Muy insatisfecho / Deficiente', 'Insatisfecho', 'Neutral / Regular', 'Satisfecho', 'Muy satisfecho / Excelente'],
            8 => ['Muy insatisfecho / Deficiente', 'Insatisfecho', 'Neutral / Regular', 'Satisfecho', 'Muy satisfecho / Excelente'],
            9 => ['Muy insatisfecho / Deficiente', 'Insatisfecho', 'Neutral / Regular', 'Satisfecho', 'Muy satisfecho / Excelente'],
            10 => ['Muy insatisfecho / Deficiente', 'Insatisfecho', 'Neutral / Regular', 'Satisfecho', 'Muy satisfecho / Excelente'],
            11 => ['Muy insatisfecho / Deficiente', 'Insatisfecho', 'Neutral / Regular', 'Satisfecho', 'Muy satisfecho / Excelente'],
            12 => ['Muy insatisfecho / Deficiente', 'Insatisfecho', 'Neutral / Regular', 'Satisfecho', 'Muy satisfecho / Excelente'],
            13 => ['Muy insatisfecho / Deficiente', 'Insatisfecho', 'Neutral / Regular', 'Satisfecho', 'Muy satisfecho / Excelente'],
            14 => ['Varias veces al día', 'Una vez al día', 'De 1 a 2 veces por semana'],
            15 => ['Limpieza', 'Falta de papel higiénico', 'Falta de jabón', 'Suministro de agua', 'Inodoros o mingitorios descompuestos', 'Puertas o seguros', 'Iluminación', 'Malos olores', 'Fugas de agua', 'Botes de basura', 'Otro'],
        ];

        foreach ($optionsByQuestion as $idPregunta => $labels) {
            $options = [];
            foreach ($labels as $index => $label) {
                $options[$index + 1] = $label;
            }

            $this->update('{{%preguntas_satisfaccion}}', [
                'opciones_json' => json_encode($options, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            ], ['id_pregunta' => $idPregunta]);
        }
    }

    public function safeDown()
    {
        $this->dropColumn('{{%preguntas_satisfaccion}}', 'escala_max');
        $this->dropColumn('{{%preguntas_satisfaccion}}', 'escala_min');
        $this->dropColumn('{{%preguntas_satisfaccion}}', 'opciones_json');
    }
}
