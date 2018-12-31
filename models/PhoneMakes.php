<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "phone_makes".
 *
 * @property integer $id
 * @property string $make
 */
class PhoneMakes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'phone_makes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['make'], 'required', ],
			[['make'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'make' => Yii::t('app', 'Make'),
        ];
    }

    /**
     * @inheritdoc
     * @return PhoneMakesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PhoneMakesQuery(get_called_class());
    }
	
}