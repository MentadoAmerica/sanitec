<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PreguntasVerificacion]].
 *
 * @see PreguntasVerificacion
 */
class PreguntasVerificacionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PreguntasVerificacion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PreguntasVerificacion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
