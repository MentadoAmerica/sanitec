<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "preguntas_verificacion".
 *
 * @property int $id_pregunta
 * @property string $pregunta
 * @property string|null $categoria
 * @property string|null $tipo_respuesta
 * @property string|null $opciones_json
 * @property string|null $respuestas_reporte_json
 * @property int $orden
 * @property int $obligatoria
 * @property int $estado
 *
 * @property RespuestasVerificacion[] $respuestasVerificacions
 * @property Verificaciones[] $verificacions
 */
class PreguntasVerificacion extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'preguntas_verificacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoria', 'tipo_respuesta', 'opciones_json', 'respuestas_reporte_json'], 'default', 'value' => null],
            [['orden'], 'default', 'value' => 0],
            [['estado'], 'default', 'value' => 1],
            [['pregunta'], 'required'],
            [['pregunta', 'opciones_json', 'respuestas_reporte_json'], 'string'],
            [['orden', 'obligatoria', 'estado'], 'integer'],
            [['categoria'], 'string', 'max' => 50],
            [['tipo_respuesta'], 'string', 'max' => 30],
            [['opciones_json', 'respuestas_reporte_json'], 'string'],
            [['orden'], 'integer'],
            [['categoria'], 'string', 'max' => 50],
            [['id_verificacion', 'id_pregunta'], 'required'],
            [['id_verificacion', 'id_pregunta'], 'integer'],
            [['respuesta'], 'string', 'max' => 255],
            [['observacion'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pregunta' => 'Id Pregunta',
            'pregunta' => 'Pregunta',
            'categoria' => 'Categoria',
            'tipo_respuesta' => 'Tipo Respuesta',
            'opciones_json' => 'Opciones Json',
            'respuestas_reporte_json' => 'Respuestas Reporte Json',
            'orden' => 'Orden',
            'obligatoria' => 'Obligatoria',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[RespuestasVerificacions]].
     *
     * @return \yii\db\ActiveQuery|RespuestasVerificacionQuery
     */
    public function getRespuestasVerificacions()
    {
        return $this->hasMany(RespuestasVerificacion::class, ['id_pregunta' => 'id_pregunta']);
    }

    /**
     * Gets query for [[Verificacions]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getVerificacions()
    {
        return $this->hasMany(Verificaciones::class, ['id_verificacion' => 'id_verificacion'])->viaTable('respuestas_verificacion', ['id_pregunta' => 'id_pregunta']);
    }

    /**
     * {@inheritdoc}
     * @return PreguntasVerificacionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PreguntasVerificacionQuery(get_called_class());
    }

    public function getOptions(): array
{
    if (empty($this->opciones_json)) {
        return [];
    }
    $decoded = json_decode($this->opciones_json, true);
    return is_array($decoded) ? $decoded : [];
}

public function getReportTriggers(): array
{
    if (empty($this->respuestas_reporte_json)) {
        return [];
    }
    $decoded = json_decode($this->respuestas_reporte_json, true);
    return is_array($decoded) ? $decoded : [];
}

/**
 * devuelve los nombres de tipos_reporte que debe generar una respuesta concreta.
 * @return string[]
 */
public function getTiposReporteParaRespuesta(string $valorRespuesta): array
{
    $triggers = $this->getReportTriggers();
    if (!isset($triggers[$valorRespuesta])) {
        return [];
    }
    $lista = $triggers[$valorRespuesta];
    if (!is_array($lista)) {
        return [(string) $lista];
    }
    return array_map('strval', $lista);
}

public static function findActivasAgrupadas(): array
{
    $preguntas = self::find()
        ->where(['estado' => 1])
        ->orderBy(['categoria' => SORT_ASC, 'orden' => SORT_ASC])
        ->all();

    $agrupadas = [
        'limpieza' => [],
        'abastecimiento' => [],
        'mantenimiento' => [],
    ];

    foreach ($preguntas as $p) {
        $g = $p->categoria ?: 'limpieza';
        if (!isset($agrupadas[$g])) {
            $agrupadas[$g] = [];
        }
        $agrupadas[$g][] = $p;
    }

    return $agrupadas;
}

public function getVerificacion()
{
    return $this->hasOne(Verificaciones::class, ['id_verificacion' => 'id_verificacion']);
}

public function getPregunta()
{
    return $this->hasOne(PreguntasVerificacion::class, ['id_pregunta' => 'id_pregunta']);
}

}
