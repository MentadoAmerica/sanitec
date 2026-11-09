<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "evaluaciones_satisfaccion".
 *
 * @property int $id_evaluacion
 * @property int $id_sanitario
 * @property string $fecha_hora
 * @property string|null $turno
 * @property int|null $calificacion_general
 * @property string|null $comentario
 *
 * @property RespuestasSatisfaccion[] $respuestasSatisfaccions
 * @property Sanitarios $sanitario
 */
class EvaluacionesSatisfaccion extends \yii\db\ActiveRecord
{
    public const TURNO_MATUTINO = 'matutino';
    public const TURNO_VESPERTINO = 'vespertino';
    public const TURNO_FUERA_DE_HORARIO = 'fuera_de_horario';

    public static function tableName()
    {
        return 'evaluaciones_satisfaccion';
    }

    public function rules()
    {
        return [
            [['id_sanitario', 'fecha_hora'], 'required'],
            [['id_sanitario', 'calificacion_general'], 'integer'],
            [['calificacion_general'], 'default', 'value' => null],
            [['fecha_hora'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['turno'], 'string', 'max' => 30],
            [['turno'], 'in', 'range' => [self::TURNO_MATUTINO, self::TURNO_VESPERTINO, self::TURNO_FUERA_DE_HORARIO]],
            [['comentario'], 'string'],
            [['id_sanitario'], 'exist', 'skipOnError' => true, 'targetClass' => Sanitarios::class, 'targetAttribute' => ['id_sanitario' => 'id_sanitario']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_evaluacion' => 'Id Evaluacion',
            'id_sanitario' => 'Sanitario',
            'fecha_hora' => 'Fecha y hora',
            'turno' => 'Turno',
            'calificacion_general' => 'Calificación general',
            'comentario' => 'Comentarios',
        ];
    }

    public function getSanitario()
    {
        return $this->hasOne(Sanitarios::class, ['id_sanitario' => 'id_sanitario']);
    }

    public function getRespuestasSatisfaccions()
    {
        return $this->hasMany(RespuestasSatisfaccion::class, ['id_evaluacion' => 'id_evaluacion']);
    }

    public static function find()
    {
        return new EvaluacionesSatisfaccionQuery(get_called_class());
    }

    public static function resolveTurno(?string $fechaHora = null): string
    {
        $date = $fechaHora === null ? new \DateTimeImmutable('now', new \DateTimeZone('America/Matamoros')) : new \DateTimeImmutable($fechaHora, new \DateTimeZone('America/Matamoros'));
        $hora = (int) $date->format('G');
        $minuto = (int) $date->format('i');
        $totalMinutos = ($hora * 60) + $minuto;

        if ($totalMinutos >= 7 * 60 && $totalMinutos < 15 * 60) {
            return self::TURNO_MATUTINO;
        }

        if ($totalMinutos >= 15 * 60 && $totalMinutos < 22 * 60) {
            return self::TURNO_VESPERTINO;
        }

        return self::TURNO_FUERA_DE_HORARIO;
    }
}
