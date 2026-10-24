<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[HorariosPersonal]].
 *
 * @see HorariosPersonal
 */
class HorariosPersonalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return HorariosPersonal[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return HorariosPersonal|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
