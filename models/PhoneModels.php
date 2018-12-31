<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "phone_models".
 *
 * @property integer $id
 * @property string $model
 * @property integer $phone_make_id
 */
class PhoneModels extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'phone_models';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone_make_id','model'], 'required', ],
            [['phone_make_id'], 'integer'],
            [['model'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model' => Yii::t('app', 'Model'),
            'phone_make_id' => Yii::t('app', 'Phone Make ID'),
        ];
    }

    /**
     * @inheritdoc
     * @return PhoneModelsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PhoneModelsQuery(get_called_class());
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoneMakes()
    {
        return $this->hasOne(PhoneMakes::className(), ['id' => 'phone_make_id']);
    }

	/* Getter for person full name */
	public function getPhoneName() {
		return $this->phoneMakes->make . ' ' . $this->model ;
	}
	

}