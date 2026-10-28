<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "personal".
 *
 * @property int $id_personal
 * @property int|null $id_usuario
 * @property string $tipo_personal
 * @property string $identificador
 * @property int $estado
 *
 * @property AsignacionesPersonal[] $asignacionesPersonals
 * @property AtencionesReportes[] $atencionesReportes
 * @property HorariosPersonal[] $horariosPersonals
 * @property MovimientosInventario[] $movimientosInventarios
 * @property Servicios[] $servicios
 * @property Usuarios $usuario
 */
class Personal extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'personal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_usuario'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['id_usuario', 'estado'], 'integer'],
            [['tipo_personal', 'identificador'], 'required'],
            [['tipo_personal'], 'string', 'max' => 50],
            [['identificador'], 'string', 'max' => 100],
            [['identificador'], 'unique'],
            [['id_usuario'], 'unique'],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::class, 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_personal' => 'Id Personal',
            'id_usuario' => 'Id Usuario',
            'tipo_personal' => 'Tipo Personal',
            'identificador' => 'Identificador',
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
        return $this->hasMany(AsignacionesPersonal::class, ['id_personal' => 'id_personal']);
    }

    /**
     * Gets query for [[AtencionesReportes]].
     *
     * @return \yii\db\ActiveQuery|AtencionesReportesQuery
     */
    public function getAtencionesReportes()
    {
        return $this->hasMany(AtencionesReportes::class, ['id_personal' => 'id_personal']);
    }

    /**
     * Gets query for [[HorariosPersonals]].
     *
     * @return \yii\db\ActiveQuery|HorariosPersonalQuery
     */
    public function getHorariosPersonals()
    {
        return $this->hasMany(HorariosPersonal::class, ['id_personal' => 'id_personal']);
    }

    /**
     * Gets query for [[MovimientosInventarios]].
     *
     * @return \yii\db\ActiveQuery|MovimientosInventarioQuery
     */
    public function getMovimientosInventarios()
    {
        return $this->hasMany(MovimientosInventario::class, ['id_personal_receptor' => 'id_personal']);
    }

    /**
     * Gets query for [[Servicios]].
     *
     * @return \yii\db\ActiveQuery|ServiciosQuery
     */
    public function getServicios()
    {
        return $this->hasMany(Servicios::class, ['id_personal' => 'id_personal']);
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery|UsuariosQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::class, ['id_usuario' => 'id_usuario']);
    }

    public function getNombreMostrar()
{
    if ($this->usuario) {
        return $this->usuario->getNombreCompleto();
    }
    return ucfirst($this->tipo_personal) . ' · ' . $this->identificador;
}
    /**
     * {@inheritdoc}
     * @return PersonalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PersonalQuery(get_called_class());
    }

}
