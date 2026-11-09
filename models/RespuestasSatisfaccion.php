<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "respuestas_satisfaccion".
 *
 * @property int $id_respuesta
 * @property int $id_evaluacion
 * @property int $id_pregunta
 * @property string|null $respuesta
 *
 * @property EvaluacionesSatisfaccion $evaluacion
 * @property PreguntasSatisfaccion $pregunta
 */
class RespuestasSatisfaccion extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'respuestas_satisfaccion';
    }

    public function rules()
    {
        return [
            [['id_evaluacion', 'id_pregunta'], 'required'],
            [['id_evaluacion', 'id_pregunta'], 'integer'],
            [['respuesta'], 'string', 'max' => 255],
            [['id_evaluacion'], 'exist', 'skipOnError' => true, 'targetClass' => EvaluacionesSatisfaccion::class, 'targetAttribute' => ['id_evaluacion' => 'id_evaluacion']],
            [['id_pregunta'], 'exist', 'skipOnError' => true, 'targetClass' => PreguntasSatisfaccion::class, 'targetAttribute' => ['id_pregunta' => 'id_pregunta']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_respuesta' => 'Id Respuesta',
            'id_evaluacion' => 'Id Evaluación',
            'id_pregunta' => 'Pregunta',
            'respuesta' => 'Respuesta',
        ];
    }

    public function getEvaluacion()
    {
        return $this->hasOne(EvaluacionesSatisfaccion::class, ['id_evaluacion' => 'id_evaluacion']);
    }

    public function getPregunta()
    {
        return $this->hasOne(PreguntasSatisfaccion::class, ['id_pregunta' => 'id_pregunta']);
    }

    public static function find()
    {
        return new RespuestasSatisfaccionQuery(get_called_class());
    }
}
