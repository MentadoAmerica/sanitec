<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AsignacionesPersonal]].
 *
 * @see AsignacionesPersonal
 */
class AsignacionesPersonalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AsignacionesPersonal[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AsignacionesPersonal|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
