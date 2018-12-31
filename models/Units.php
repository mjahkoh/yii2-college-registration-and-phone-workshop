<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "units".
 *
 * @property integer $id
 * @property string $unit
 */
class Units extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'units';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    { 
        return [
            [['unit'], 'required'],
			[['unit'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'unit' => Yii::t('app', 'Unit'),
        ];
    }

    /**
     * @inheritdoc
     * @return UnitsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UnitsQuery(get_called_class());
    }
}
