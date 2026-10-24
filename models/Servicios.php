<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "servicios".
 *
 * @property int $id_servicio
 * @property int $id_personal
 * @property int $id_sanitario
 * @property string $fecha
 * @property string $hora_checkin
 * @property string|null $hora_checkout
 * @property string|null $observaciones
 *
 * @property Personal $personal
 * @property Sanitarios $sanitario
 */
class Servicios extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'servicios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hora_checkout', 'observaciones'], 'default', 'value' => null],
            [['id_personal', 'id_sanitario', 'fecha', 'hora_checkin'], 'required'],
            [['id_personal', 'id_sanitario'], 'integer'],
            [['fecha', 'hora_checkin', 'hora_checkout'], 'safe'],
            [['observaciones'], 'string'],
            [['id_personal'], 'exist', 'skipOnError' => true, 'targetClass' => Personal::class, 'targetAttribute' => ['id_personal' => 'id_personal']],
            [['id_sanitario'], 'exist', 'skipOnError' => true, 'targetClass' => Sanitarios::class, 'targetAttribute' => ['id_sanitario' => 'id_sanitario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_servicio' => 'Id Servicio',
            'id_personal' => 'Id Personal',
            'id_sanitario' => 'Id Sanitario',
            'fecha' => 'Fecha',
            'hora_checkin' => 'Hora Checkin',
            'hora_checkout' => 'Hora Checkout',
            'observaciones' => 'Observaciones',
        ];
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
     * Gets query for [[Sanitario]].
     *
     * @return \yii\db\ActiveQuery|SanitariosQuery
     */
    public function getSanitario()
    {
        return $this->hasOne(Sanitarios::class, ['id_sanitario' => 'id_sanitario']);
    }

    /**
     * {@inheritdoc}
     * @return ServiciosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ServiciosQuery(get_called_class());
    }

}
