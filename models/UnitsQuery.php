<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Units]].
 *
 * @see Units
 */
class UnitsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Units[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Units|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
