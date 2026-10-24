<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "edificios".
 *
 * @property int $id_edificio
 * @property string $nombre
 * @property string|null $descripcion
 * @property string|null $ubicacion
 * @property int $estado
 *
 * @property AsignacionesPersonal[] $asignacionesPersonals
 * @property MovimientosInventario[] $movimientosInventarios
 * @property Sanitarios[] $sanitarios
 */
class Edificios extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'edificios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'ubicacion'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['nombre'], 'required'],
            [['descripcion'], 'string'],
            [['estado'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['ubicacion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_edificio' => 'Id Edificio',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'ubicacion' => 'Ubicacion',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[AsignacionesPersonals]].
     *
     * @return \yii\db\ActiveQuery|AsignacionesPersonalQuery
     */
    public function getAsignacionesPersonals()
    {
        return $this->hasMany(AsignacionesPersonal::class, ['id_edificio' => 'id_edificio']);
    }

    /**
     * Gets query for [[MovimientosInventarios]].
     *
     * @return \yii\db\ActiveQuery|MovimientosInventarioQuery
     */
    public function getMovimientosInventarios()
    {
        return $this->hasMany(MovimientosInventario::class, ['id_edificio' => 'id_edificio']);
    }

    /**
     * Gets query for [[Sanitarios]].
     *
     * @return \yii\db\ActiveQuery|SanitariosQuery
     */
    public function getSanitarios()
    {
        return $this->hasMany(Sanitarios::class, ['id_edificio' => 'id_edificio']);
    }

    /**
     * {@inheritdoc}
     * @return EdificiosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EdificiosQuery(get_called_class());
    }

}
