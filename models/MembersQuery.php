<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Members]].
 *
 * @see Members
 */
class MembersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Members[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Members|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
