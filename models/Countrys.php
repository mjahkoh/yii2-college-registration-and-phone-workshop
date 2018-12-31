<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "countrys".
 *
 * @property int $id
 * @property string $country
 * @property string $currency
 * @property int $international_tel_code
 */
class Countrys extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'countrys';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['international_tel_code'], 'integer'],
            [['country'], 'string', 'max' => 20],
            [['currency'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country' => 'Country',
            'currency' => 'Currency',
            'international_tel_code' => 'International Tel Code',
        ];
    }

    /**
     * @inheritdoc
     * @return CountrysQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CountrysQuery(get_called_class());
    }
}
