<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "preguntas_satisfaccion".
 *
 * @property int $id_pregunta
 * @property string $pregunta
 * @property string|null $categoria
 * @property int $estado
 * @property string|null $opciones_json
 * @property int $escala_min
 * @property int $escala_max
 *
 * @property RespuestasSatisfaccion[] $respuestasSatisfaccions
 */
class PreguntasSatisfaccion extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'preguntas_satisfaccion';
    }

    public function rules()
    {
        return [
            [['pregunta'], 'required'],
            [['estado'], 'default', 'value' => 1],
            [['escala_min'], 'default', 'value' => 1],
            [['escala_max'], 'default', 'value' => 5],
            [['estado', 'escala_min', 'escala_max'], 'integer'],
            [['estado'], 'in', 'range' => [0, 1]],
            [['pregunta'], 'string'],
            [['opciones_json'], 'string'],
            [['categoria'], 'string', 'max' => 50],
            [['escala_min'], 'integer', 'min' => 1, 'max' => 10],
            [['escala_max'], 'integer', 'min' => 2, 'max' => 10],
            [['escala_max'], 'compare', 'compareAttribute' => 'escala_min', 'operator' => '>='],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_pregunta' => 'Id Pregunta',
            'pregunta' => 'Pregunta',
            'categoria' => 'Categoría',
            'estado' => 'Estado',
            'opciones_json' => 'Opciones',
            'escala_min' => 'Valor mínimo',
            'escala_max' => 'Valor máximo',
        ];
    }

    public function getOptions(): array
    {
        if ($this->opciones_json === null || $this->opciones_json === '') {
            return [];
        }

        $options = json_decode($this->opciones_json, true);
        if (!is_array($options)) {
            return [];
        }

        $normalized = [];
        foreach ($options as $value => $label) {
            if (is_numeric($value) && is_string($label)) {
                $normalized[(int) $value] = $label;
            }
        }

        return $normalized;
    }

    public function getRespuestasSatisfaccions()
    {
        return $this->hasMany(RespuestasSatisfaccion::class, ['id_pregunta' => 'id_pregunta']);
    }

}
