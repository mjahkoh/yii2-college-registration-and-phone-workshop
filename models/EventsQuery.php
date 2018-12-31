<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Events]].
 *
 * @see Events
 */
class EventsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Events[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Events|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
