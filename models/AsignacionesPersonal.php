<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asignaciones_personal".
 *
 * @property int $id_asignacion
 * @property int $id_personal
 * @property int $id_edificio
 * @property string $fecha_inicio
 * @property string|null $fecha_fin
 * @property int $estado
 *
 * @property Edificios $edificio
 * @property Personal $personal
 */
class AsignacionesPersonal extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asignaciones_personal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_fin'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['id_personal', 'id_edificio', 'fecha_inicio'], 'required'],
            [['id_personal', 'id_edificio', 'estado'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['id_edificio'], 'exist', 'skipOnError' => true, 'targetClass' => Edificios::class, 'targetAttribute' => ['id_edificio' => 'id_edificio']],
            [['id_personal'], 'exist', 'skipOnError' => true, 'targetClass' => Personal::class, 'targetAttribute' => ['id_personal' => 'id_personal']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_asignacion' => 'Id Asignacion',
            'id_personal' => 'Id Personal',
            'id_edificio' => 'Id Edificio',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[Edificio]].
     *
     * @return \yii\db\ActiveQuery|EdificiosQuery
     */
    public function getEdificio()
    {
        return $this->hasOne(Edificios::class, ['id_edificio' => 'id_edificio']);
    }

    /**
     * Gets query for [[Personal]].
     *
     * @return \yii\db\ActiveQuery|PersonalQuery
     */
    public function getPersonal()
    {
        return $this->hasOne(Personal::class, ['id_personal' => 'id_personal']);
    }

    /**
     * {@inheritdoc}
     * @return AsignacionesPersonalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AsignacionesPersonalQuery(get_called_class());
    }

}
