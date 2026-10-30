<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id_ronda
 * @property int $id_sanitario
 * @property int $id_horario_ss
 * @property string $fecha_programada
 * @property string $hora_programada
 * @property string $estado
 * @property string|null $fecha_hora_inicio
 * @property string|null $fecha_hora_fin
 */
class RondasProgramadas extends ActiveRecord
{
    public static function tableName()
    {
        return 'rondas_programadas';
    }

    public function rules()
    {
        return [
            [['estado'], 'default', 'value' => 'Pendiente'],
            [['fecha_hora_inicio', 'fecha_hora_fin'], 'default', 'value' => null],
            [['id_sanitario', 'id_horario_ss', 'fecha_programada', 'hora_programada'], 'required'],
            [['id_sanitario', 'id_horario_ss'], 'integer'],
            [['fecha_programada', 'hora_programada', 'fecha_hora_inicio', 'fecha_hora_fin'], 'safe'],
            [['fecha_programada'], 'date', 'format' => 'php:Y-m-d'],
            [['hora_programada'], 'match', 'pattern' => '/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/'],
            [['estado'], 'string', 'max' => 30],
            [
                ['id_sanitario', 'id_horario_ss', 'fecha_programada', 'hora_programada'],
                'unique',
                'targetAttribute' => ['id_sanitario', 'id_horario_ss', 'fecha_programada', 'hora_programada'],
                'message' => 'Ya existe una ronda para ese sanitario, horario, fecha y hora.',
            ],
            [['id_sanitario'], 'exist', 'skipOnError' => true, 'targetClass' => Sanitarios::class, 'targetAttribute' => ['id_sanitario' => 'id_sanitario']],
            [['id_horario_ss'], 'exist', 'skipOnError' => true, 'targetClass' => HorariosServicioSocial::class, 'targetAttribute' => ['id_horario_ss' => 'id_horario_ss']],
        ];
    }

    public function getSanitario()
    {
        return $this->hasOne(Sanitarios::class, ['id_sanitario' => 'id_sanitario']);
    }

    public function getHorarioSs()
    {
        return $this->hasOne(HorariosServicioSocial::class, ['id_horario_ss' => 'id_horario_ss']);
    }
}
