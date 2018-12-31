<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "departments".
 *
 * @property integer $id
 * @property string $name
 * @property integer $branch_id
 * @property integer $company_id
 *
 * @property Branches $id0
 */
class Departments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'departments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'branch_id', 'company_id'], 'required'],
			[['branch_id', 'company_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Branches::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'branch_id' => Yii::t('app', 'Branch'),
            'company_id' => Yii::t('app', 'Company'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranches()
    {
        return $this->hasOne(Branches::className(), ['id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }


    /**
     * @inheritdoc
     * @return DepartmentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DepartmentsQuery(get_called_class());
    }
}
