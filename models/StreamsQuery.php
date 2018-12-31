<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Streams]].
 *
 * @see Streams
 */
class StreamsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Streams[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Streams|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
