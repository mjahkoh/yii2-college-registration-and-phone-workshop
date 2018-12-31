<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "uscitysv_citys".
 *
 * @property integer $city_id
 * @property string $city
 * @property integer $county_id
 * @property string $city_ascii
 *
 * @property UscitiesvCountys $county
 */
class Citys extends \yii\db\ActiveRecord
{
   
   public $state;
   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uscitysv_citys';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['county_id'], 'integer'],
			[['city', 'county_id', 'city_ascii', 'state'], 'required'],
            [['city'], 'string', 'max' => 50],
			[['counties.state_id'], 'safe'],
            [['city_ascii'], 'string', 'max' => 255],
            [['county_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countys::className(), 'targetAttribute' => ['county_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'city_id' => Yii::t('app', 'City ID'),
            'city' => Yii::t('app', 'City'),
            'county_id' => Yii::t('app', 'County'),
            'city_ascii' => Yii::t('app', 'City Ascii'),
			'counties.state_id' => Yii::t('app', 'State'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountys()
    {
        return $this->hasOne(Countys::className(), ['id' => 'county_id']);
    }

    /**
     * @inheritdoc
     * @return CitysQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CitysQuery(get_called_class());
    }
}
