<?php

declare(strict_types=1);

namespace app\models;

use yii\base\Model;

class RegistroForm extends Model
{
    private const GENERAL_ROLE_ID = 5;

    public string $nombre = '';
    public string $apellidos = '';
    public string $correo = '';
    public string $telefono = '';
    public string $contrasena = '';
    public string $confirmarContrasena = '';

    public function rules(): array
    {
        return [
            [['nombre', 'apellidos', 'correo', 'contrasena', 'confirmarContrasena'], 'required',
                'message' => 'Este campo es obligatorio.'],
            [['nombre', 'apellidos'], 'trim'],
            [['nombre', 'apellidos'], 'string', 'max' => 100],
            ['correo', 'trim'],
            ['correo', 'email', 'message' => 'Ingresa un correo electrónico válido.'],
            ['correo', 'string', 'max' => 150],
            ['telefono', 'trim'],
            ['telefono', 'string', 'max' => 20],
            ['contrasena', 'string', 'min' => 8, 'tooShort' => 'La contraseña debe tener al menos 8 caracteres.'],
            [
                'confirmarContrasena',
                'compare',
                'compareAttribute' => 'contrasena',
                'message' => 'Las contraseñas no coinciden.',
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
            'correo' => 'Correo institucional',
            'telefono' => 'Teléfono (opcional)',
            'contrasena' => 'Contraseña',
            'confirmarContrasena' => 'Confirmar contraseña',
        ];
    }

    public function registrar(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        if (!Roles::find()->where([
            'id_rol' => self::GENERAL_ROLE_ID,
            'estado' => 1,
        ])->exists()) {
            $this->addError('correo', 'El registro no está disponible en este momento. Contacta al administrador.');
            return false;
        }

        $usuario = new User();
        $usuario->id_rol = self::GENERAL_ROLE_ID;
        $usuario->nombre = $this->nombre;
        $usuario->apellidos = $this->apellidos;
        $usuario->correo = $this->correo;
        $usuario->telefono = $this->telefono !== '' ? $this->telefono : null;
        $usuario->estado = 1;
        $usuario->setPassword($this->contrasena);

        if ($usuario->save()) {
            return true;
        }

        foreach ($usuario->getErrors() as $attribute => $errors) {
            foreach ($errors as $error) {
                $this->addError($attribute, $error);
            }
        }

        return false;
    }
}
