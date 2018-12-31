<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MarksDetail]].
 *
 * @see MarksDetail
 */
class MarksDetailQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MarksDetail[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MarksDetail|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
