<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UnitsBookedByStudents]].
 *
 * @see UnitsBookedByStudents
 */
class UnitsBookedByStudentsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UnitsBookedByStudents[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UnitsBookedByStudents|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
