<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[StudentGames]].
 *
 * @see StudentGames
 */
class StudentGamesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return StudentGames[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StudentGames|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
