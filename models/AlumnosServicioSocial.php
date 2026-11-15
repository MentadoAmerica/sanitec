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
        [['id_usuario'], 'required'],
        [['id_usuario', 'estado'], 'integer'],
        [['matricula'], 'string', 'max' => 30],
        [['pin'], 'string', 'max' => 10],
        [['pin'], 'match', 'pattern' => '/^[0-9]{4,10}$/', 'message' => 'El PIN debe tener entre 4 y 10 dígitos numéricos.'],
        [['id_usuario'], 'unique'],
        [['matricula'], 'unique'],
        [['pin'], 'unique'],
        [['id_usuario'], 'exist', 'targetClass' => Usuarios::class, 'targetAttribute' => 'id_usuario'],
        [['estado'], 'default', 'value' => 1],
    ];
}

public function beforeSave($insert)
{
    if (!parent::beforeSave($insert)) {
        return false;
    }

    if ($insert && empty($this->pin)) {
        $this->pin = self::generatePin();
    }

    return true;
}

public static function generatePin(): string
{
    do {
        $pin = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    } while (self::find()->where(['pin' => $pin])->exists());

    return $pin;
}

public static function findByPin(string $pin): ?self
{
    $pin = trim($pin);
    if ($pin === '' || !preg_match('/^[0-9]{4,10}$/', $pin)) {
        return null;
    }
    return self::find()
        ->where(['pin' => $pin, 'estado' => 1])
        ->one();
}

public function attributeLabels()
{
    return [
        'id_alumno' => 'ID',
        'id_usuario' => 'Cuenta vinculada',
        'matricula' => 'Matrícula',
        'pin' => 'PIN personal',
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
