<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "verificaciones".
 *
 * @property int $id_verificacion
 * @property int $id_ronda
 * @property int $id_alumno
 * @property int $id_sanitario
 * @property string $fecha_hora_inicio
 * @property string|null $fecha_hora_fin
 * @property string|null $resultado_general
 * @property string|null $observaciones
 * @property string|null $checklist_json
 * @property string|null $evidencia_foto
 *
 * @property RondasProgramadas $ronda
 * @property AlumnosServicioSocial $alumno
 * @property Sanitarios $sanitario
 * @property RespuestasVerificacion[] $respuestasVerificacions
 */
class Verificaciones extends ActiveRecord
{
    /**
     * atributo temporal para el archivo subido (no va a la BD)
     * @var \yii\web\UploadedFile|null
     */
    public $evidenciaFile;

    public static function tableName()
    {
        return 'verificaciones';
    }

    public function rules()
    {
        return [
            [['id_ronda', 'id_alumno', 'id_sanitario', 'fecha_hora_inicio'], 'required'],
            [['id_ronda', 'id_alumno', 'id_sanitario'], 'integer'],
            [['fecha_hora_inicio', 'fecha_hora_fin'], 'safe'],
            [['resultado_general'], 'string', 'max' => 50],
            [['observaciones', 'checklist_json'], 'string'],
            [['evidencia_foto'], 'string', 'max' => 500],
            [['evidenciaFile'], 'file',
                'extensions' => ['jpg', 'jpeg', 'png', 'webp'],
                'maxSize' => 5 * 1024 * 1024,
                'skipOnEmpty' => true,
            ],
            [['id_ronda'], 'exist', 'skipOnError' => true,
                'targetClass' => RondasProgramadas::class,
                'targetAttribute' => ['id_ronda' => 'id_ronda'],
            ],
            [['id_alumno'], 'exist', 'skipOnError' => true,
                'targetClass' => AlumnosServicioSocial::class,
                'targetAttribute' => ['id_alumno' => 'id_alumno'],
            ],
            [['id_sanitario'], 'exist', 'skipOnError' => true,
                'targetClass' => Sanitarios::class,
                'targetAttribute' => ['id_sanitario' => 'id_sanitario'],
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_verificacion' => 'ID',
            'id_ronda' => 'Ronda',
            'id_alumno' => 'Alumno',
            'id_sanitario' => 'Sanitario',
            'fecha_hora_inicio' => 'Inicio',
            'fecha_hora_fin' => 'Fin',
            'resultado_general' => 'Resultado general',
            'observaciones' => 'Observaciones',
            'checklist_json' => 'Checklist',
            'evidencia_foto' => 'Evidencia fotográfica',
        ];
    }

    /**
     * mueve el archivo de evidencia al directorio definitivo y devuelve la ruta relativa.
     * si no hay archivo nuevo, devuelve la ruta anterior (o null).
     */
    public function uploadEvidencia(): ?string
    {
        if (!$this->evidenciaFile) {
            return $this->evidencia_foto;
        }

        $dir = Yii::getAlias('@webroot/uploads/verificaciones/' . date('Y/m'));
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $nombre = Yii::$app->security->generateRandomString(16)
            . '.' . $this->evidenciaFile->extension;
        $rutaCompleta = $dir . '/' . $nombre;

        if ($this->evidenciaFile->saveAs($rutaCompleta)) {
            // borrar la foto anterior si existe (en edición)
            if (!empty($this->evidencia_foto) && !$this->isNewRecord) {
                $anterior = Yii::getAlias('@webroot/' . $this->evidencia_foto);
                if (is_file($anterior)) {
                    @unlink($anterior);
                }
            }
            return 'uploads/verificaciones/' . date('Y/m') . '/' . $nombre;
        }

        return $this->evidencia_foto;
    }

    // ===== relaciones =====

    public function getRonda()
    {
        return $this->hasOne(RondasProgramadas::class, ['id_ronda' => 'id_ronda']);
    }

    public function getAlumno()
    {
        return $this->hasOne(AlumnosServicioSocial::class, ['id_alumno' => 'id_alumno']);
    }

    public function getSanitario()
    {
        return $this->hasOne(Sanitarios::class, ['id_sanitario' => 'id_sanitario']);
    }

    public function getRespuestasVerificacions()
    {
        return $this->hasMany(RespuestasVerificacion::class, ['id_verificacion' => 'id_verificacion']);
    }

    public static function find()
    {
        return new VerificacionesQuery(get_called_class());
    }
}