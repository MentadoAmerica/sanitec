<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Verificaciones]].
 *
 * @see Verificaciones
 */
class VerificacionesQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Verificaciones[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Verificaciones|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}