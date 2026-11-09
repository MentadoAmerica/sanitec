<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\Security;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $correo;
    public $contrasena;

    private $_user = false;
    private ?Security $_security = null;

    public function __construct(?Security $security = null, array $config = [])
    {
        $this->_security = $security ?? Yii::$app->security;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['username', 'required', 'when' => fn () => empty($this->correo), 'message' => 'Username cannot be blank.'],
            ['password', 'required', 'when' => fn () => empty($this->contrasena), 'message' => 'Password cannot be blank.'],
            ['correo', 'required', 'when' => fn () => empty($this->username), 'message' => 'Este campo es obligatorio.'],
            ['contrasena', 'required', 'when' => fn () => empty($this->password), 'message' => 'Este campo es obligatorio.'],
            ['username', 'string', 'max' => 255],
            ['correo', 'email'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['contrasena', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
            'correo' => 'Correo institucional',
            'contrasena' => 'Contraseña',
            'rememberMe' => 'Remember me',
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if ($this->hasErrors()) {
            return;
        }

        $user = $this->getUser();
        $passwordValue = $this->password ?? $this->contrasena ?? null;
        if (!$user || !$user->validatePassword((string) $passwordValue)) {
            $this->addError($attribute, 'Incorrect username or password.');
        }
    }

    public function login()
    {
        if ($this->validate()) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0;
            return Yii::$app->user->login($this->getUser(), $duration);
        }

        return false;
    }

    protected function getUser()
    {
        if ($this->_user === false) {
            $identifier = $this->username ?? $this->correo ?? null;
            if ($identifier === null) {
                return null;
            }

            $this->_user = User::findByUsername((string) $identifier);
        }

        return $this->_user;
    }
}