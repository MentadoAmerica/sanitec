<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MovimientosInventario]].
 *
 * @see MovimientosInventario
 */
class MovimientosInventarioQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MovimientosInventario[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MovimientosInventario|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
