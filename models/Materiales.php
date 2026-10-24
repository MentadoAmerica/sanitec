<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "materiales".
 *
 * @property int $id_material
 * @property string $nombre
 * @property string|null $descripcion
 * @property string|null $unidad_medida
 * @property float $stock_actual
 * @property float $stock_minimo
 * @property float|null $costo_unitario
 * @property int $estado
 *
 * @property MovimientosInventario[] $movimientosInventarios
 */
class Materiales extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'materiales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'unidad_medida', 'costo_unitario'], 'default', 'value' => null],
            [['stock_minimo'], 'default', 'value' => 0.00],
            [['estado'], 'default', 'value' => 1],
            [['nombre'], 'required'],
            [['descripcion'], 'string'],
            [['stock_actual', 'stock_minimo', 'costo_unitario'], 'number'],
            [['estado'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['unidad_medida'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_material' => 'Id Material',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'unidad_medida' => 'Unidad Medida',
            'stock_actual' => 'Stock Actual',
            'stock_minimo' => 'Stock Minimo',
            'costo_unitario' => 'Costo Unitario',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[MovimientosInventarios]].
     *
     * @return \yii\db\ActiveQuery|MovimientosInventarioQuery
     */
    public function getMovimientosInventarios()
    {
        return $this->hasMany(MovimientosInventario::class, ['id_material' => 'id_material']);
    }

    /**
     * {@inheritdoc}
     * @return MaterialesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MaterialesQuery(get_called_class());
    }

}
