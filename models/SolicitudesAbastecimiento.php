<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id_solicitud
 * @property int|null $id_reporte
 * @property int $id_material
 * @property int $id_usuario_solicita
 * @property float $cantidad_solicitada
 * @property string $fecha_solicitud
 * @property string $estado
 * @property string|null $observaciones
 */
class SolicitudesAbastecimiento extends ActiveRecord
{
    public static function tableName()
    {
        return 'solicitudes_abastecimiento';
    }

    public function rules()
    {
        return [
            [['id_reporte', 'observaciones'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 'Pendiente'],
            [['id_material', 'id_usuario_solicita', 'cantidad_solicitada', 'fecha_solicitud'], 'required'],
            [['id_reporte', 'id_material', 'id_usuario_solicita'], 'integer'],
            [['cantidad_solicitada'], 'number', 'min' => 0.01],
            [['fecha_solicitud'], 'safe'],
            [['observaciones'], 'string', 'max' => 2000],
            [['estado'], 'string', 'max' => 30],
            [['id_reporte'], 'exist', 'skipOnError' => true, 'targetClass' => Reportes::class, 'targetAttribute' => ['id_reporte' => 'id_reporte']],
            [['id_material'], 'exist', 'skipOnError' => true, 'targetClass' => Materiales::class, 'targetAttribute' => ['id_material' => 'id_material']],
            [['id_usuario_solicita'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::class, 'targetAttribute' => ['id_usuario_solicita' => 'id_usuario']],
        ];
    }

    public function getReporte()
    {
        return $this->hasOne(Reportes::class, ['id_reporte' => 'id_reporte']);
    }

    public function getMaterial()
    {
        return $this->hasOne(Materiales::class, ['id_material' => 'id_material']);
    }

    public function getUsuarioSolicita()
    {
        return $this->hasOne(Usuarios::class, ['id_usuario' => 'id_usuario_solicita']);
    }
}
