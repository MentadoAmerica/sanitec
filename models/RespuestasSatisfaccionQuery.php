<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[\app\models\RespuestasSatisfaccion]].
 *
 * @see \app\models\RespuestasSatisfaccion
 */
class RespuestasSatisfaccionQuery extends \yii\db\ActiveQuery
{
    public function byEvaluacion($idEvaluacion)
    {
        return $this->andWhere(['id_evaluacion' => $idEvaluacion]);
    }

    public function byPregunta($idPregunta)
    {
        return $this->andWhere(['id_pregunta' => $idPregunta]);
    }
}
