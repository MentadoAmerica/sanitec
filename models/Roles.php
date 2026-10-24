<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "roles".
 *
 * @property int $id_rol
 * @property string $nombre
 * @property string|null $descripcion
 * @property int $estado
 *
 * @property Usuarios[] $usuarios
 */
class Roles extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['nombre'], 'required'],
            [['descripcion'], 'string'],
            [['estado'], 'integer'],
            [['nombre'], 'string', 'max' => 50],
            [['nombre'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_rol' => 'Id Rol',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[Usuarios]].
     *
     * @return \yii\db\ActiveQuery|UsuariosQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::class, ['id_rol' => 'id_rol']);
    }

    /**
     * {@inheritdoc}
     * @return RolesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RolesQuery(get_called_class());
    }

}
