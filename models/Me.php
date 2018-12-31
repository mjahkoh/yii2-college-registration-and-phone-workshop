<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "me".
 *
 * @property integer $account_no
 * @property string $firstname
 * @property string $middlename
 * @property string $surname
 * @property integer $line
 * @property string $tel1
 * @property string $tel2
 * @property integer $meter_no
 * @property string $physical_location
 * @property string $address
 */
class Me extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'me';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['registration_date'], 'safe'],
            [['line', 'meter_no'], 'integer'],
            [['physical_location', 'address'], 'string'],
            [['firstname', 'middlename', 'surname', 'tel1', 'tel2'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account_no' => Yii::t('app', 'Account No'),
            'firstname' => Yii::t('app', 'Firstname'),
            'middlename' => Yii::t('app', 'Middlename'),
            'surname' => Yii::t('app', 'Surname'),
            'line' => Yii::t('app', 'Line'),
            'tel1' => Yii::t('app', 'Tel1'),
            'tel2' => Yii::t('app', 'Tel2'),
            'meter_no' => Yii::t('app', 'Meter No'),
            'physical_location' => Yii::t('app', 'Physical Location'),
            'address' => Yii::t('app', 'Address'),
        ];
    }
}
