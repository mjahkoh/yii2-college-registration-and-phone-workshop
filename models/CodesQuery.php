<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Codes]].
 *
 * @see Codes
 */
class CodesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Codes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Codes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
