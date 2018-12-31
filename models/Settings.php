<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $setting
 * @property string $value
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setting', 'value'], 'required'],
		    [['setting', 'value'], 'string', 'max' => 50],
			[['value'], 'isSettingValid'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'setting' => 'Setting',
            'value' => 'Value',
        ];
    }

    /**
     * @inheritdoc
     * @return SettingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingsQuery(get_called_class());
    }
	
	public function isSettingValid($attribute, $params)
	{
		if ($this->setting == 'default_country_prefix'  && !is_numeric($this->value)) {
			$this->addError($attribute, "The Country Prefix should be numeric ");
		}
	}
	
    public static function getDefaultCountryPrefix()
    {
		$sql = "SELECT value FROM settings where setting = 'default_country_prefix' limit 1";
		$check = Yii::$app->db->createCommand($sql)->queryOne();
		if ( $check['value']  ){
			return $check['value'];
		}	
		return NULL;
    }
	
}