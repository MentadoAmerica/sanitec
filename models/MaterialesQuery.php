<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Materiales]].
 *
 * @see Materiales
 */
class MaterialesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Materiales[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Materiales|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
