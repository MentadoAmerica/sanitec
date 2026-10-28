<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alumno_horarios_ss".
 *
 * @property int $id_alumno_horario
 * @property int $id_alumno
 * @property int $id_horario_ss
 * @property string $fecha_inicio
 * @property string|null $fecha_fin
 * @property int $estado
 *
 * @property AlumnosServicioSocial $alumno
 * @property HorariosServicioSocial $horarioSs
 */
class AlumnoHorariosSs extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alumno_horarios_ss';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_fin'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['id_alumno', 'id_horario_ss', 'fecha_inicio'], 'required'],
            [['id_alumno', 'id_horario_ss', 'estado'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['id_alumno', 'id_horario_ss', 'fecha_inicio'], 'unique', 'targetAttribute' => ['id_alumno', 'id_horario_ss', 'fecha_inicio']],
            [['id_alumno'], 'exist', 'skipOnError' => true, 'targetClass' => AlumnosServicioSocial::class, 'targetAttribute' => ['id_alumno' => 'id_alumno']],
            [['id_horario_ss'], 'exist', 'skipOnError' => true, 'targetClass' => HorariosServicioSocial::class, 'targetAttribute' => ['id_horario_ss' => 'id_horario_ss']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_alumno_horario' => 'Id Alumno Horario',
            'id_alumno' => 'Id Alumno',
            'id_horario_ss' => 'Id Horario Ss',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[Alumno]].
     *
     * @return \yii\db\ActiveQuery|AlumnosServicioSocialQuery
     */

    public function getAlumno()
{
    return $this->hasOne(AlumnosServicioSocial::class, ['id_alumno' => 'id_alumno']);
}

public function getHorarioSs()
{
    return $this->hasOne(HorariosServicioSocial::class, ['id_horario_ss' => 'id_horario_ss']);
}

    /**
     * {@inheritdoc}
     * @return AlumnoHorariosSsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AlumnoHorariosSsQuery(get_called_class());
    }

}
