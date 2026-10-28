<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "horarios_servicio_social".
 *
 * @property int $id_horario_ss
 * @property string $nombre_turno
 * @property string $dia_semana
 * @property string $hora_inicio
 * @property string $hora_fin
 * @property int $estado
 *
 * @property AlumnoHorariosSs[] $alumnoHorariosSses
 * @property RondasProgramadas[] $rondasProgramadas
 */
class HorariosServicioSocial extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'horarios_servicio_social';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estado'], 'default', 'value' => 1],
            [['nombre_turno', 'dia_semana', 'hora_inicio', 'hora_fin'], 'required'],
            [['hora_inicio', 'hora_fin'], 'safe'],
            [['estado'], 'integer'],
            [['nombre_turno'], 'string', 'max' => 50],
            [['dia_semana'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_horario_ss' => 'Id Horario Ss',
            'nombre_turno' => 'Nombre Turno',
            'dia_semana' => 'Dia Semana',
            'hora_inicio' => 'Hora Inicio',
            'hora_fin' => 'Hora Fin',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[AlumnoHorariosSses]].
     *
     * @return \yii\db\ActiveQuery|AlumnoHorariosSsQuery
     */
    public function getAlumnoHorariosSses()
    {
        return $this->hasMany(AlumnoHorariosSs::class, ['id_horario_ss' => 'id_horario_ss']);
    }

    /**
     * Gets query for [[RondasProgramadas]].
     *
     * @return \yii\db\ActiveQuery|RondasProgramadasQuery
     */
    public function getRondasProgramadas()
    {
        return $this->hasMany(RondasProgramadas::class, ['id_horario_ss' => 'id_horario_ss']);
    }

    /**
     * {@inheritdoc}
     * @return HorariosServicioSocialQuery the active query used by this AR class.
     */
    public function getPersonal()
{
    return $this->hasOne(Personal::class, ['id_personal' => 'id_personal']);
}
    public static function find()
    {
        return new HorariosServicioSocialQuery(get_called_class());
    }

}
