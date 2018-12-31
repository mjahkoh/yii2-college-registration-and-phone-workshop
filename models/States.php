<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "uscitiesv_states".
 *
 * @property integer $state_id
 * @property string $state_code
 * @property string $state_name
 *
 * @property UscitiesvCountys[] $uscitiesvCountys
 */
class States extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uscitiesv_states';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state_code', 'state_name'], 'required'],
		    [['state_code'], 'string', 'max' => 3],
            [['state_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'state_id' => Yii::t('app', 'State ID'),
            'state_code' => Yii::t('app', 'State Code'),
            'state_name' => Yii::t('app', 'State Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountys()
    {
        return $this->hasMany(Countys::className(), ['state_id' => 'state_id']);
    }

    /**
     * @inheritdoc
     * @return StatesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StatesQuery(get_called_class());
    }
}
