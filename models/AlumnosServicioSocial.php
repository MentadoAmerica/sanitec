<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alumnos_servicio_social".
 *
 * @property int $id_alumno
 * @property int $id_usuario
 * @property string|null $matricula
 * @property int $estado
 *
 * @property AlumnoHorariosSs[] $alumnoHorariosSses
 * @property Usuarios $usuario
 * @property Verificaciones[] $verificaciones
 */
class AlumnosServicioSocial extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alumnos_servicio_social';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matricula'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['id_usuario'], 'required'],
            [['id_usuario', 'estado'], 'integer'],
            [['matricula'], 'string', 'max' => 30],
            [['id_usuario'], 'unique'],
            [['matricula'], 'unique'],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::class, 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_alumno' => 'Id Alumno',
            'id_usuario' => 'Id Usuario',
            'matricula' => 'Matricula',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[AlumnoHorariosSses]].
     *
     * @return \yii\db\ActiveQuery|AlumnoHorariosSsQuery
     */
    public function getAlumnoHorariosSses()
    {
        return $this->hasMany(AlumnoHorariosSs::class, ['id_alumno' => 'id_alumno']);
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
     * Gets query for [[Verificaciones]].
     *
     * @return \yii\db\ActiveQuery|VerificacionesQuery
     */
    public function getVerificaciones()
    {
        return $this->hasMany(Verificaciones::class, ['id_alumno' => 'id_alumno']);
    }

    /**
     * {@inheritdoc}
     * @return AlumnosServicioSocialQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AlumnosServicioSocialQuery(get_called_class());
    }

}
