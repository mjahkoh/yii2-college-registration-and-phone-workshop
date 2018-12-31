<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Games]].
 *
 * @see Games
 */
class GamesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Games[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Games|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
