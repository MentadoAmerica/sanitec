<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Usuarios extends ActiveRecord
{
    public static function tableName()
    {
        return 'usuarios';
    }

    public function rules()
    {
        return [
            [['id_rol', 'nombre', 'apellidos', 'correo'], 'required'],
            [['id_rol'], 'integer'],
            [['id_rol'], 'exist', 'targetClass' => Roles::class, 'targetAttribute' => 'id_rol'],
            [['estado'], 'boolean'],
            [['nombre', 'apellidos'], 'string', 'max' => 100],
            [['correo'], 'email'],
            [['correo'], 'string', 'max' => 150],
            [['correo'], 'unique'],
            [['telefono'], 'string', 'max' => 20],
            [['contrasena'], 'string'],
            [['contrasena'], 'required', 'on' => 'create'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_usuario' => 'ID',
            'id_rol' => 'Rol',
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
            'correo' => 'Correo',
            'telefono' => 'Teléfono',
            'contrasena' => 'Contraseña',
            'estado' => 'Estado',
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            // en alta siempre se hashea
            if (!empty($this->contrasena)) {
                $this->contrasena = Yii::$app->security->generatePasswordHash($this->contrasena);
            }
        } else {
            // en edición solo se hashea si el usuario escribió una nueva
            if (!empty($this->contrasena) && $this->isAttributeChanged('contrasena')) {
                $this->contrasena = Yii::$app->security->generatePasswordHash($this->contrasena);
            } else {
                // conserva el hash anterior si el campo quedó vacío
                $this->contrasena = $this->getOldAttribute('contrasena');
            }
        }

        return true;
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