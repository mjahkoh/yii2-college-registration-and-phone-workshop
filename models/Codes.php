<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "codes".
 *
 * @property int $id
 * @property string $code
 */
class Codes extends \yii\db\ActiveRecord
{
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'codes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
        ];
    }

    /**
     * @inheritdoc
     * @return CodesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CodesQuery(get_called_class());
    }
	
	
}