<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property int $id_usuario
 * @property int $id_rol
 * @property string $nombre
 * @property string $apellidos
 * @property string $correo
 * @property string $contrasena
 * @property int $estado
 * @property string|null $telefono
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const STATUS_DELETED = 0;
    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;

    public static function tableName()
    {
        $schema = static::getDb()->getSchema();
        return $schema->getTableSchema('user', true) !== null ? 'user' : 'usuarios';
    }

    public static function primaryKey()
    {
        if (static::getDb()->getSchema()->getTableSchema('user', true) !== null) {
            return ['id'];
        }

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

    public static function findIdentity($id)
    {
        $schema = static::getDb()->getSchema();
        if ($schema->getTableSchema('user', true) !== null) {
            return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE])
                ?: static::findOne(['id' => $id]);
        }

        return static::findOne(['id_usuario' => $id, 'estado' => 1]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $schema = static::getDb()->getSchema();
        if ($schema->getTableSchema('user', true) !== null) {
            return static::findOne(['access_token' => $token]);
        }

        return null;
    }

    public static function findByUsername($username)
    {
        $schema = static::getDb()->getSchema();
        if ($schema->getTableSchema('user', true) !== null) {
            return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE])
                ?: static::findOne(['username' => $username]);
        }

        return static::findOne(['correo' => $username, 'estado' => 1]);
    }

    public function getId()
    {
        return $this->id ?? $this->id_usuario ?? null;
    }

    public function getAuthKey()
    {
        return $this->auth_key ?? null;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        $hash = $this->password_hash ?? $this->contrasena ?? null;
        if ($hash === null || $hash === '') {
            return false;
        }

        return Yii::$app->security->validatePassword($password, $hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        if (isset($this->contrasena)) {
            $this->contrasena = $this->password_hash;
        }
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function getRol()
    {
        return $this->hasOne(Roles::class, ['id_rol' => 'id_rol']);
    }

    public function getNombreCompleto()
    {
        return trim(($this->nombre ?? '') . ' ' . ($this->apellidos ?? ''));
    }
}