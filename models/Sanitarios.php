<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sanitarios".
 *
 * @property int $id_sanitario
 * @property int $id_edificio
 * @property string $nombre
 * @property string|null $tipo
 * @property string|null $ubicacion
 * @property string $codigo_qr
 * @property int $estado
 *
 * @property Edificios $edificio
 * @property EvaluacionesSatisfaccion[] $evaluacionesSatisfaccions
 * @property MovimientosInventario[] $movimientosInventarios
 * @property Reportes[] $reportes
 * @property RondasProgramadas[] $rondasProgramadas
 * @property Servicios[] $servicios
 * @property Verificaciones[] $verificaciones
 */
class Sanitarios extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sanitarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo', 'ubicacion'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['id_edificio', 'nombre', 'codigo_qr'], 'required'],
            [['id_edificio', 'estado'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['tipo'], 'string', 'max' => 50],
            [['ubicacion', 'codigo_qr'], 'string', 'max' => 255],
            [['codigo_qr'], 'unique'],
            [['id_edificio'], 'exist', 'skipOnError' => true, 'targetClass' => Edificios::class, 'targetAttribute' => ['id_edificio' => 'id_edificio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_sanitario' => 'Id Sanitario',
            'id_edificio' => 'Id Edificio',
            'nombre' => 'Nombre',
            'tipo' => 'Tipo',
            'ubicacion' => 'Ubicacion',
            'codigo_qr' => 'Codigo Qr',
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
     * Gets query for [[EvaluacionesSatisfaccions]].
     *
     * @return \yii\db\ActiveQuery|EvaluacionesSatisfaccionQuery
     */
    public function getEvaluacionesSatisfaccions()
    {
        return $this->hasMany(EvaluacionesSatisfaccion::class, ['id_sanitario' => 'id_sanitario']);
    }

    /**
     * Gets query for [[MovimientosInventarios]].
     *
     * @return \yii\db\ActiveQuery|MovimientosInventarioQuery
     */
    public function getMovimientosInventarios()
    {
        return $this->hasMany(MovimientosInventario::class, ['id_sanitario' => 'id_sanitario']);
    }

    /**
     * Gets query for [[Reportes]].
     *
     * @return \yii\db\ActiveQuery|ReportesQuery
     */
    public function getReportes()
    {
        return $this->hasMany(Reportes::class, ['id_sanitario' => 'id_sanitario']);
    }

    /**
     * Gets query for [[RondasProgramadas]].
     *
     * @return \yii\db\ActiveQuery|RondasProgramadasQuery
     */
    public function getRondasProgramadas()
    {
        return $this->hasMany(RondasProgramadas::class, ['id_sanitario' => 'id_sanitario']);
    }

    /**
     * Gets query for [[Servicios]].
     *
     * @return \yii\db\ActiveQuery|ServiciosQuery
     */
    public function getServicios()
    {
        return $this->hasMany(Servicios::class, ['id_sanitario' => 'id_sanitario']);
    }

    /**
     * Gets query for [[Verificaciones]].
     *
     * @return \yii\db\ActiveQuery|VerificacionesQuery
     */
    public function getVerificaciones()
    {
        return $this->hasMany(Verificaciones::class, ['id_sanitario' => 'id_sanitario']);
    }

    /**
     * {@inheritdoc}
     * @return SanitariosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SanitariosQuery(get_called_class());
    }

}
