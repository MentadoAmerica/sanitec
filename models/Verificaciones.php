<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id_verificacion
 * @property int $id_ronda
 * @property int $id_alumno
 * @property int $id_sanitario
 * @property string $fecha_hora_inicio
 * @property string|null $fecha_hora_fin
 * @property string|null $resultado_general
 * @property string|null $observaciones
 * @property string|null $checklist_json
 * @property string|null $evidencia_foto
 */
class Verificaciones extends ActiveRecord
{
    public static function tableName()
    {
        return 'verificaciones';
    }

    public function rules()
    {
        return [
            [['fecha_hora_fin', 'resultado_general', 'observaciones', 'checklist_json', 'evidencia_foto'], 'default', 'value' => null],
            [['id_ronda', 'id_alumno', 'id_sanitario', 'fecha_hora_inicio'], 'required'],
            [['id_ronda', 'id_alumno', 'id_sanitario'], 'integer'],
            [['fecha_hora_inicio', 'fecha_hora_fin'], 'safe'],
            [['observaciones', 'checklist_json'], 'string'],
            [['resultado_general'], 'string', 'max' => 50],
            [['evidencia_foto'], 'string', 'max' => 500],
        ];
    }

    public function getAlumno()
    {
        return $this->hasOne(AlumnosServicioSocial::class, ['id_alumno' => 'id_alumno']);
    }

    public function getSanitario()
    {
        return $this->hasOne(Sanitarios::class, ['id_sanitario' => 'id_sanitario']);
    }
}
