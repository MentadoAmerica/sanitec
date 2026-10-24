<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id_usuario
 * @property int $id_rol
 * @property string $nombre
 * @property string $apellidos
 * @property string $correo
 * @property string|null $telefono
 * @property string $contrasena
 * @property int $estado
 *
 * @property AlumnosServicioSocial $alumnosServicioSocial
 * @property HistorialReportes[] $historialReportes
 * @property MovimientosInventario[] $movimientosInventarios
 * @property Notificaciones[] $notificaciones
 * @property Personal $personal
 * @property Reportes[] $reportes
 * @property Roles $rol
 */
class Usuarios extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['telefono'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['id_rol', 'nombre', 'apellidos', 'correo', 'contrasena'], 'required'],
            [['id_rol', 'estado'], 'integer'],
            [['nombre', 'apellidos'], 'string', 'max' => 100],
            [['correo'], 'string', 'max' => 150],
            [['telefono'], 'string', 'max' => 20],
            [['contrasena'], 'string', 'max' => 255],
            [['correo'], 'unique'],
            [['id_rol'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::class, 'targetAttribute' => ['id_rol' => 'id_rol']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'Id Usuario',
            'id_rol' => 'Id Rol',
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
            'correo' => 'Correo',
            'telefono' => 'Telefono',
            'contrasena' => 'Contrasena',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[AlumnosServicioSocial]].
     *
     * @return \yii\db\ActiveQuery|AlumnosServicioSocialQuery
     */
    public function getAlumnosServicioSocial()
    {
        return $this->hasOne(AlumnosServicioSocial::class, ['id_usuario' => 'id_usuario']);
    }

    /**
     * Gets query for [[HistorialReportes]].
     *
     * @return \yii\db\ActiveQuery|HistorialReportesQuery
     */
    public function getHistorialReportes()
    {
        return $this->hasMany(HistorialReportes::class, ['id_usuario' => 'id_usuario']);
    }

    /**
     * Gets query for [[MovimientosInventarios]].
     *
     * @return \yii\db\ActiveQuery|MovimientosInventarioQuery
     */
    public function getMovimientosInventarios()
    {
        return $this->hasMany(MovimientosInventario::class, ['id_usuario_registra' => 'id_usuario']);
    }

    /**
     * Gets query for [[Notificaciones]].
     *
     * @return \yii\db\ActiveQuery|NotificacionesQuery
     */
    public function getNotificaciones()
    {
        return $this->hasMany(Notificaciones::class, ['id_usuario_destinatario' => 'id_usuario']);
    }

    /**
     * Gets query for [[Personal]].
     *
     * @return \yii\db\ActiveQuery|PersonalQuery
     */
    public function getPersonal()
    {
        return $this->hasOne(Personal::class, ['id_usuario' => 'id_usuario']);
    }

    /**
     * Gets query for [[Reportes]].
     *
     * @return \yii\db\ActiveQuery|ReportesQuery
     */
    public function getReportes()
    {
        return $this->hasMany(Reportes::class, ['id_usuario_reporta' => 'id_usuario']);
    }

    /**
     * Gets query for [[Rol]].
     *
     * @return \yii\db\ActiveQuery|RolesQuery
     */
    public function getRol()
    {
        return $this->hasOne(Roles::class, ['id_rol' => 'id_rol']);
    }

    /**
     * {@inheritdoc}
     * @return UsuariosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsuariosQuery(get_called_class());
    }

}
