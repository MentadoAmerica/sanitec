<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[HorariosServicioSocial]].
 *
 * @see HorariosServicioSocial
 */
class HorariosServicioSocialQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return HorariosServicioSocial[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return HorariosServicioSocial|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
