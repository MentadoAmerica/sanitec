<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[\app\models\EvaluacionesSatisfaccion]].
 *
 * @see \app\models\EvaluacionesSatisfaccion
 */
class EvaluacionesSatisfaccionQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['>', 'id_evaluacion', 0]);
    }

    public function bySanitario($idSanitario)
    {
        return $this->andWhere(['id_sanitario' => $idSanitario]);
    }

    public function byTurno($turno)
    {
        return $this->andWhere(['turno' => $turno]);
    }
}
