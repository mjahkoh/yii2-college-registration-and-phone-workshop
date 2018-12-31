<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AuthItem]].
 *
 * @see AuthItem
 */
class AuthItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AuthItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AuthItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
