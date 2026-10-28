<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AlumnoHorariosSs]].
 *
 * @see AlumnoHorariosSs
 */
class AlumnoHorariosSsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AlumnoHorariosSs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AlumnoHorariosSs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
