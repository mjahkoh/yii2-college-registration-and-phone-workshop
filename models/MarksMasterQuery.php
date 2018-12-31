<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MarksMaster]].
 *
 * @see MarksMaster
 */
class MarksMasterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MarksMaster[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MarksMaster|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
