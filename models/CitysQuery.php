<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Citys]].
 *
 * @see Citys
 */
class CitysQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Citys[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Citys|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
