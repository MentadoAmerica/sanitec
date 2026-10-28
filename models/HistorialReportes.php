<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "historial_reportes".
 *
 * @property int $id_historial
 * @property int $id_reporte
 * @property int|null $id_usuario
 * @property string|null $estado_anterior
 * @property string|null $estado_nuevo
 * @property string $fecha_hora
 * @property string|null $observaciones
 *
 * @property Reportes $reporte
 * @property Usuarios $usuario
 */
class HistorialReportes extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historial_reportes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_usuario', 'estado_anterior', 'estado_nuevo', 'observaciones'], 'default', 'value' => null],
            [['id_reporte', 'fecha_hora'], 'required'],
            [['id_reporte', 'id_usuario'], 'integer'],
            [['fecha_hora'], 'safe'],
            [['observaciones'], 'string'],
            [['estado_anterior', 'estado_nuevo'], 'string', 'max' => 30],
            [['id_reporte'], 'exist', 'skipOnError' => true, 'targetClass' => Reportes::class, 'targetAttribute' => ['id_reporte' => 'id_reporte']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::class, 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_historial' => 'Id Historial',
            'id_reporte' => 'Id Reporte',
            'id_usuario' => 'Id Usuario',
            'estado_anterior' => 'Estado Anterior',
            'estado_nuevo' => 'Estado Nuevo',
            'fecha_hora' => 'Fecha Hora',
            'observaciones' => 'Observaciones',
        ];
    }

    /**
     * Gets query for [[Reporte]].
     *
     * @return \yii\db\ActiveQuery|ReportesQuery
     */
    public function getReporte()
    {
        return $this->hasOne(Reportes::class, ['id_reporte' => 'id_reporte']);
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::class, ['id_usuario' => 'id_usuario']);
    }

    /**
     * {@inheritdoc}
     * @return HistorialReportesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new HistorialReportesQuery(get_called_class());
    }

}
