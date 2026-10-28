<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[HistorialReportes]].
 *
 * @see HistorialReportes
 */
class HistorialReportesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return HistorialReportes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return HistorialReportes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
