<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[JobsMembers]].
 *
 * @see JobsMembers
 */
class MembersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return JobsMembers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return JobsMembers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
