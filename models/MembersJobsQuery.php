<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MembersJobs]].
 *
 * @see MembersJobs
 */
class MembersJobsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MembersJobs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MembersJobs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
