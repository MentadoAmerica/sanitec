<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipos_reporte".
 *
 * @property int $id_tipo_reporte
 * @property string $nombre
 * @property string|null $categoria
 * @property string|null $prioridad
 * @property int $estado
 *
 * @property Reportes[] $reportes
 */
class TiposReporte extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipos_reporte';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoria', 'prioridad'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['nombre'], 'required'],
            [['estado'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['categoria'], 'string', 'max' => 50],
            [['prioridad'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_reporte' => 'Id Tipo Reporte',
            'nombre' => 'Nombre',
            'categoria' => 'Categoria',
            'prioridad' => 'Prioridad',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[Reportes]].
     *
     * @return \yii\db\ActiveQuery|ReportesQuery
     */
    public function getReportes()
    {
        return $this->hasMany(Reportes::class, ['id_tipo_reporte' => 'id_tipo_reporte']);
    }

    /**
     * {@inheritdoc}
     * @return TiposReporteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TiposReporteQuery(get_called_class());
    }

}
