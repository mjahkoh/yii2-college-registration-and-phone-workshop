<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

use Yii;

/**
 * This is the model class for table "branches".
 *
 * @property integer $id
 * @property string $branch_name
 * @property string $address
 * @property string $date_created
 * @property integer $status
 * @property string $location
 */
class Branches extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'branches';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_created', 'address', 'location', 'branch_name', 'status', 'companies_company_id'], 'required'],
			[['date_created', 'companies_company_id'], 'safe'],
            [['status'], 'integer'],
            [['branch_name'], 'string', 'max' => 50],
            [['address', 'location'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'branch_name' => Yii::t('app', 'Branch'),
            'address' => Yii::t('app', 'Address'),
            'date_created' => Yii::t('app', 'Date Created'),
            'status' => Yii::t('app', 'Status'),
            'location' => Yii::t('app', 'Location'),
			'companies_company_id' => Yii::t('app', 'Company'),
			'companies.company_name' => Yii::t('app', 'Company'),
        ];
    }

    /**
     * @inheritdoc
     * @return BranchesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BranchesQuery(get_called_class());
    }
	
	/*
	before save can be used for instance in formatting dates before saving.
	*/
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			// ...custom code here...
			$this->date_created = Yii::$app->formatter->asDate($this->date_created,'yyyy-MM-dd HH:mm:ss');
			return true;
		} else {
			return false;
		}
	}
	
    /**
     * @return \yii\db\ActiveQuery
     */
    //public function getCompanies()
	public function getCompanies()
    {
        return $this->hasOne(Companies::className(), ['id' =>'companies_company_id']);
    }

	public function behaviors ()
	{
		return [
		[
		'class' =>	TimestampBehavior::className(),
		'attributes' => [
				ActiveRecord::EVENT_BEFORE_INSERT => [ 'date_created'  ],
		],
		// if you're using datetime instead of UNIX timestamp:
		// 'value' => new Expression('NOW()'),
		],
		];
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments()
    {
        return $this->hasMany(Departments::className(), ['branch_id' => 'id']);
    }

	
}
