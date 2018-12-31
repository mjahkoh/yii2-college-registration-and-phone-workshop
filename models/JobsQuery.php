<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Jobs]].
 *
 * @see Jobs
 */
class JobsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Jobs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Jobs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
