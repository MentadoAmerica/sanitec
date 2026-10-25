<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $correo;
    public $contrasena;
    public $rememberMe = false;

    private $_user = false;

    public function rules()
    {
        return [
            [['correo', 'contrasena'], 'required'],
            ['correo', 'email'],
            ['rememberMe', 'boolean'],
            ['contrasena', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'correo'     => 'Correo institucional',
            'contrasena' => 'Contraseña',
            'rememberMe' => 'Recordarme',
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->contrasena)) {
                $this->addError($attribute, 'Correo o contraseña incorrectos.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 0);
        }
        return false;
    }

    protected function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findOne([
                'correo' => $this->correo,
                'estado' => 1,
            ]);
        }
        return $this->_user;
    }
}