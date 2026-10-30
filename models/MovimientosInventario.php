<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "movimientos_inventario".
 *
 * @property int $id_movimiento
 * @property int $id_material
 * @property int $id_usuario_registra
 * @property int|null $id_personal_receptor
 * @property int|null $id_edificio
 * @property int|null $id_sanitario
 * @property string $tipo_movimiento
 * @property float $cantidad
 * @property float|null $costo_unitario
 * @property float|null $costo_total
 * @property string $fecha_hora
 * @property string|null $proveedor
 * @property string|null $folio_documento
 * @property string|null $observaciones
 *
 * @property Edificios $edificio
 * @property Materiales $material
 * @property Personal $personalReceptor
 * @property Sanitarios $sanitario
 * @property Usuarios $usuarioRegistra
 */
class MovimientosInventario extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'movimientos_inventario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_personal_receptor', 'id_edificio', 'id_sanitario', 'costo_unitario', 'costo_total', 'proveedor', 'folio_documento', 'observaciones'], 'default', 'value' => null],
            [['id_material', 'id_usuario_registra', 'tipo_movimiento', 'cantidad', 'fecha_hora'], 'required'],
            [['id_material', 'id_usuario_registra', 'id_personal_receptor', 'id_edificio', 'id_sanitario'], 'integer'],
            [['cantidad', 'costo_unitario', 'costo_total'], 'number'],
            [['fecha_hora'], 'safe'],
            [['observaciones'], 'string'],
            [['tipo_movimiento'], 'string', 'max' => 30],
            [['proveedor'], 'string', 'max' => 150],
            [['folio_documento'], 'string', 'max' => 100],
            [['id_edificio'], 'exist', 'skipOnError' => true, 'targetClass' => Edificios::class, 'targetAttribute' => ['id_edificio' => 'id_edificio']],
            [['id_material'], 'exist', 'skipOnError' => true, 'targetClass' => Materiales::class, 'targetAttribute' => ['id_material' => 'id_material']],
            [['id_personal_receptor'], 'exist', 'skipOnError' => true, 'targetClass' => Personal::class, 'targetAttribute' => ['id_personal_receptor' => 'id_personal']],
            [['id_sanitario'], 'exist', 'skipOnError' => true, 'targetClass' => Sanitarios::class, 'targetAttribute' => ['id_sanitario' => 'id_sanitario']],
            [['id_usuario_registra'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::class, 'targetAttribute' => ['id_usuario_registra' => 'id_usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_movimiento' => 'Id Movimiento',
            'id_material' => 'Id Material',
            'id_usuario_registra' => 'Id Usuario Registra',
            'id_personal_receptor' => 'Id Personal Receptor',
            'id_edificio' => 'Id Edificio',
            'id_sanitario' => 'Id Sanitario',
            'tipo_movimiento' => 'Tipo Movimiento',
            'cantidad' => 'Cantidad',
            'costo_unitario' => 'Costo Unitario',
            'costo_total' => 'Costo Total',
            'fecha_hora' => 'Fecha Hora',
            'proveedor' => 'Proveedor',
            'folio_documento' => 'Folio Documento',
            'observaciones' => 'Observaciones',
        ];
    }

    /**
     * Gets query for [[Edificio]].
     *
     * @return \yii\db\ActiveQuery|EdificiosQuery
     */
    public function getEdificio()
    {
        return $this->hasOne(Edificios::class, ['id_edificio' => 'id_edificio']);
    }

    /**
     * Gets query for [[Material]].
     *
     * @return \yii\db\ActiveQuery|MaterialesQuery
     */
    public function getMaterial()
    {
        return $this->hasOne(Materiales::class, ['id_material' => 'id_material']);
    }

    /**
     * Gets query for [[PersonalReceptor]].
     *
     * @return \yii\db\ActiveQuery|PersonalQuery
     */
    public function getPersonalReceptor()
    {
        return $this->hasOne(Personal::class, ['id_personal' => 'id_personal_receptor']);
    }

    /**
     * Gets query for [[Sanitario]].
     *
     * @return \yii\db\ActiveQuery|SanitariosQuery
     */
    public function getSanitario()
    {
        return $this->hasOne(Sanitarios::class, ['id_sanitario' => 'id_sanitario']);
    }

    /**
     * Gets query for [[UsuarioRegistra]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getUsuarioRegistra()
    {
        return $this->hasOne(Usuarios::class, ['id_usuario' => 'id_usuario_registra']);
    }

    /**
     * {@inheritdoc}
     * @return MovimientosInventarioQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MovimientosInventarioQuery(get_called_class());
    }

}
