<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AlumnosServicioSocial]].
 *
 * @see AlumnosServicioSocial
 */
class AlumnosServicioSocialQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AlumnosServicioSocial[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AlumnosServicioSocial|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
