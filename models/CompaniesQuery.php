<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Companies]].
 *
 * @see Companies
 */
class CompaniesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Companies[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Companies|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
