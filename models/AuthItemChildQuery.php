<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AuthItemChild]].
 *
 * @see AuthItemChild
 */
class AuthItemChildQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AuthItemChild[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AuthItemChild|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
