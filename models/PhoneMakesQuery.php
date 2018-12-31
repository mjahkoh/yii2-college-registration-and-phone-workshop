<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PhoneMakes]].
 *
 * @see PhoneMakes
 */
class PhoneMakesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PhoneMakes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PhoneMakes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
