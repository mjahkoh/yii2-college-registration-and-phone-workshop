<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[CompaniesFiles]].
 *
 * @see CompaniesFiles
 */
class CompaniesFilesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CompaniesFiles[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CompaniesFiles|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
