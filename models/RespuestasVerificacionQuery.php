<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[RespuestasVerificacion]].
 *
 * @see RespuestasVerificacion
 */
class RespuestasVerificacionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RespuestasVerificacion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RespuestasVerificacion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
