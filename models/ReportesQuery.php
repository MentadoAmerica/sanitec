<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Reportes]].
 *
 * @see Reportes
 */
class ReportesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Reportes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Reportes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
