<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Branches]].
 *
 * @see Branches
 */
class BranchesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Branches[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Branches|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
