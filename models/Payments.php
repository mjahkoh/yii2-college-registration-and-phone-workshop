<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payments".
 *
 * @property integer $id
 * @property integer $job_id
 * @property integer $amount
 */
class Payments extends \yii\db\ActiveRecord
{
		public $charges = 0;
		public $jobid;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['job_id', 'amount', 'date_of_payment'], 'required'],
			[['job_id', 'amount'], 'integer'],
			[['charges', 'date_of_payment'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'job_id' => Yii::t('app', 'Job ID'),
            'amount' => Yii::t('app', 'Amount'),
			'charges' => Yii::t('app', 'Charges'),
			'date_of_payment' => Yii::t('app', 'Date of payment'),
        ];
    }

    /**
     * @inheritdoc
     * @return PaymentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PaymentsQuery(get_called_class());
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobs()
    {
        return $this->hasOne(Jobs::className(), ['id' => 'job_id']);
    }

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			// ...custom code here...'yyyy-MM-dd HH:mm:ss'
			$this->date_of_payment = Yii::$app->formatter->asDate($this->date_of_payment,'yyyy-MM-dd');
			return true;
		} else {
			return false;
		}
		
		//we first save the clientelle if its a new guy
	}


}