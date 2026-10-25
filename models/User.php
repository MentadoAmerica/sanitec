<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'usuarios';
    }

    public static function primaryKey()
    {
        return ['id_usuario'];
    }

    public function rules()
    {
        return [
            [['id_rol', 'nombre', 'apellidos', 'correo', 'contrasena'], 'required'],
            [['id_rol'], 'integer'],
            [['estado'], 'boolean'],
            [['nombre', 'apellidos'], 'string', 'max' => 100],
            [['correo'], 'email'],
            [['correo'], 'unique'],
            [['telefono'], 'string', 'max' => 20],
        ];
    }

    // ===== IdentityInterface =====

    public static function findIdentity($id)
    {
        return static::findOne(['id_usuario' => $id, 'estado' => 1]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->id_usuario;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    // ===== Login =====

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->contrasena);
    }

    public function setPassword($password)
    {
        $this->contrasena = Yii::$app->security->generatePasswordHash($password);
    }

    // ===== Relaciones =====

    public function getRol()
    {
        return $this->hasOne(Roles::class, ['id_rol' => 'id_rol']);
    }

    public function getNombreCompleto()
    {
        return trim(($this->nombre ?? '') . ' ' . ($this->apellidos ?? ''));
    }
}