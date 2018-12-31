<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "uscitiesv_countys".
 *
 * @property string $county
 * @property integer $id
 * @property integer $state_id
 *
 * @property UscitiesvStates $state
 * @property UscitysvCitys[] $uscitysvCitys
 */
class Countys extends \yii\db\ActiveRecord
{

		const ENTRY_FEE 		= 1;
		const SHARE_CAPITAL 	= 2;
		const LOAN_PAYMENT 		= 3;
		const DEPOSITS 			= 4;
        public $paymenttype = [
            self::ENTRY_FEE     	=> 'Entry Fee',
            self::SHARE_CAPITAL 	=> 'Share Capital',
			self::LOAN_PAYMENT 		=> 'Loan Repayment',
			self::DEPOSITS 			=> 'Deposits',
        ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uscitiesv_countys';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['county', 'id', 'state_id'], 'required'],
            [['id', 'state_id'], 'integer'],
            [['county'], 'string', 'max' => 255],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => States::className(), 'targetAttribute' => ['state_id' => 'state_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'county' => Yii::t('app', 'County'),
            'id' => Yii::t('app', 'County'),
            'state_id' => Yii::t('app', 'State'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStates()
    {
        return $this->hasOne(States::className(), ['state_id' => 'state_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCitys()
    {
        return $this->hasMany(Citys::className(), ['id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return CountysQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CountysQuery(get_called_class());
    }
}
