<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PhoneModels]].
 *
 * @see PhoneModels
 */
class PhoneModelsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PhoneModels[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PhoneModels|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
