<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "respuestas_verificacion".
 *
 * @property int $id_respuesta
 * @property int $id_verificacion
 * @property int $id_pregunta
 * @property string|null $respuesta
 * @property string|null $observacion
 *
 * @property PreguntasVerificacion $pregunta
 * @property Verificaciones $verificacion
 */
class RespuestasVerificacion extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'respuestas_verificacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['respuesta', 'observacion'], 'default', 'value' => null],
            [['id_verificacion', 'id_pregunta'], 'required'],
            [['id_verificacion', 'id_pregunta'], 'integer'],
            [['observacion'], 'string'],
            [['respuesta'], 'string', 'max' => 255],
            [['id_verificacion', 'id_pregunta'], 'unique', 'targetAttribute' => ['id_verificacion', 'id_pregunta']],
            [['id_pregunta'], 'exist', 'skipOnError' => true, 'targetClass' => PreguntasVerificacion::class, 'targetAttribute' => ['id_pregunta' => 'id_pregunta']],
            [['id_verificacion'], 'exist', 'skipOnError' => true, 'targetClass' => Verificaciones::class, 'targetAttribute' => ['id_verificacion' => 'id_verificacion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_respuesta' => 'Id Respuesta',
            'id_verificacion' => 'Id Verificacion',
            'id_pregunta' => 'Id Pregunta',
            'respuesta' => 'Respuesta',
            'observacion' => 'Observacion',
        ];
    }

    /**
     * Gets query for [[Pregunta]].
     *
     * @return \yii\db\ActiveQuery|PreguntasVerificacionQuery
     */
    public function getPregunta()
    {
        return $this->hasOne(PreguntasVerificacion::class, ['id_pregunta' => 'id_pregunta']);
    }

    /**
     * Gets query for [[Verificacion]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getVerificacion()
    {
        return $this->hasOne(Verificaciones::class, ['id_verificacion' => 'id_verificacion']);
    }

    /**
     * {@inheritdoc}
     * @return RespuestasVerificacionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RespuestasVerificacionQuery(get_called_class());
    }

}
