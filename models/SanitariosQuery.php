<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Sanitarios]].
 *
 * @see Sanitarios
 */
class SanitariosQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Sanitarios[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Sanitarios|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
