<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "horarios_personal".
 *
 * @property int $id_horario
 * @property int $id_personal
 * @property string $dia_semana
 * @property string $hora_inicio
 * @property string $hora_fin
 * @property int $estado
 *
 * @property Personal $personal
 */
class HorariosPersonal extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'horarios_personal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estado'], 'default', 'value' => 1],
            [['id_personal', 'dia_semana', 'hora_inicio', 'hora_fin'], 'required'],
            [['id_personal', 'estado'], 'integer'],
            [['hora_inicio', 'hora_fin'], 'safe'],
            [['dia_semana'], 'string', 'max' => 15],
            [['id_personal'], 'exist', 'skipOnError' => true, 'targetClass' => Personal::class, 'targetAttribute' => ['id_personal' => 'id_personal']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_horario' => 'Id Horario',
            'id_personal' => 'Id Personal',
            'dia_semana' => 'Dia Semana',
            'hora_inicio' => 'Hora Inicio',
            'hora_fin' => 'Hora Fin',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[Personal]].
     *
     * @return \yii\db\ActiveQuery|PersonalQuery
     */
    public function getPersonal()
    {
        return $this->hasOne(Personal::class, ['id_personal' => 'id_personal']);
    }

    /**
     * {@inheritdoc}
     * @return HorariosPersonalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new HorariosPersonalQuery(get_called_class());
    }

}
