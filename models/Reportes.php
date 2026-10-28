<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reportes".
 *
 * @property int $id_reporte
 * @property int $id_sanitario
 * @property int $id_tipo_reporte
 * @property int|null $id_verificacion
 * @property int $id_usuario_reporta
 * @property string $fecha_hora_reporte
 * @property string|null $descripcion
 * @property string|null $evidencia_foto
 * @property string|null $prioridad
 * @property string $estado
 * @property string|null $fecha_cierre
 *
 * @property AtencionesReportes[] $atencionesReportes
 * @property HistorialReportes[] $historialReportes
 * @property Notificaciones[] $notificaciones
 * @property Sanitarios $sanitario
 * @property TiposReporte $tipoReporte
 * @property Usuarios $usuarioReporta
 * @property Verificaciones $verificacion
 */
class Reportes extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reportes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_verificacion', 'descripcion', 'evidencia_foto', 'prioridad', 'fecha_cierre'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 'Pendiente'],
            [['id_sanitario', 'id_tipo_reporte', 'id_usuario_reporta', 'fecha_hora_reporte'], 'required'],
            [['id_sanitario', 'id_tipo_reporte', 'id_verificacion', 'id_usuario_reporta'], 'integer'],
            [['fecha_hora_reporte', 'fecha_cierre'], 'safe'],
            [['descripcion'], 'string'],
            [['evidencia_foto'], 'string', 'max' => 500],
            [['prioridad'], 'string', 'max' => 20],
            [['estado'], 'string', 'max' => 30],
            [['id_sanitario'], 'exist', 'skipOnError' => true, 'targetClass' => Sanitarios::class, 'targetAttribute' => ['id_sanitario' => 'id_sanitario']],
            [['id_tipo_reporte'], 'exist', 'skipOnError' => true, 'targetClass' => TiposReporte::class, 'targetAttribute' => ['id_tipo_reporte' => 'id_tipo_reporte']],
            [['id_usuario_reporta'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::class, 'targetAttribute' => ['id_usuario_reporta' => 'id_usuario']],
            [['id_verificacion'], 'exist', 'skipOnError' => true, 'targetClass' => Verificaciones::class, 'targetAttribute' => ['id_verificacion' => 'id_verificacion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_reporte' => 'Id Reporte',
            'id_sanitario' => 'Id Sanitario',
            'id_tipo_reporte' => 'Id Tipo Reporte',
            'id_verificacion' => 'Id Verificacion',
            'id_usuario_reporta' => 'Id Usuario Reporta',
            'fecha_hora_reporte' => 'Fecha Hora Reporte',
            'descripcion' => 'Descripcion',
            'evidencia_foto' => 'Evidencia Foto',
            'prioridad' => 'Prioridad',
            'estado' => 'Estado',
            'fecha_cierre' => 'Fecha Cierre',
        ];
    }

    /**
     * Gets query for [[AtencionesReportes]].
     *
     * @return \yii\db\ActiveQuery|AtencionesReportesQuery
     */
    public function getAtencionesReportes()
    {
        return $this->hasMany(AtencionesReportes::class, ['id_reporte' => 'id_reporte']);
    }

    /**
     * Gets query for [[HistorialReportes]].
     *
     * @return \yii\db\ActiveQuery|HistorialReportesQuery
     */
    public function getHistorialReportes()
    {
        return $this->hasMany(HistorialReportes::class, ['id_reporte' => 'id_reporte']);
    }

    /**
     * Gets query for [[Notificaciones]].
     *
     * @return \yii\db\ActiveQuery|NotificacionesQuery
     */
    public function getNotificaciones()
    {
        return $this->hasMany(Notificaciones::class, ['id_reporte' => 'id_reporte']);
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
     * Gets query for [[TipoReporte]].
     *
     * @return \yii\db\ActiveQuery|TiposReporteQuery
     */
    public function getTipoReporte()
    {
        return $this->hasOne(TiposReporte::class, ['id_tipo_reporte' => 'id_tipo_reporte']);
    }

    /**
     * Gets query for [[UsuarioReporta]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getUsuarioReporta()
    {
        return $this->hasOne(Usuarios::class, ['id_usuario' => 'id_usuario_reporta']);
    }

    /**
     * Gets query for [[Verificacion]].
     *
     * @return \yii\db\ActiveQuery|VerificacionesQuery
     */
    public function getVerificacion()
    {
        return $this->hasOne(Verificaciones::class, ['id_verificacion' => 'id_verificacion']);
    }

    public function getClaseEstado()
{
    $mapa = [
        'pendiente' => 'admin-badge-warning',
        'en atención' => 'admin-badge-info',
        'en atencion' => 'admin-badge-info',
        'atendido' => 'admin-badge-active',
        'cerrado' => 'admin-badge-muted',
    ];
    $key = mb_strtolower(trim($this->estado), 'UTF-8');
    return $mapa[$key] ?? 'admin-badge-muted';
}

public function getSiguientesEstados()
{
    $e = mb_strtolower(trim($this->estado), 'UTF-8');
    if ($e === 'cerrado') {
        return [];
    }
    if ($e === 'pendiente' || $e === '') {
        return ['En atención', 'Atendido', 'Cerrado'];
    }
    if ($e === 'en atención' || $e === 'en atencion') {
        return ['Atendido', 'Cerrado'];
    }
    if ($e === 'atendido') {
        return ['Cerrado'];
    }
    return [];
}

public function getTiempoTranscurrido()
{
    $inicio = strtotime($this->fecha_hora_reporte);
    $fin = $this->fecha_cierre ? strtotime($this->fecha_cierre) : time();
    $min = max(0, (int) floor(($fin - $inicio) / 60));
    if ($min < 60) {
        return $min . ' min';
    }
    return intdiv($min, 60) . ' h ' . ($min % 60) . ' min';
}

    /**
     * {@inheritdoc}
     * @return ReportesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReportesQuery(get_called_class());
    }

}
