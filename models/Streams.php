<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "streams".
 *
 * @property integer $id
 * @property string $stream
 * @property string $year
 */
class Streams extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'streams';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stream', 'year'], 'required'],
			[['year'], 'safe'],
            [['stream'], 'string', 'max' => 30],
			['stream', 'checkDuplicate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'stream' => Yii::t('app', 'Stream'),
            'year' => Yii::t('app', 'Year'),
        ];
    }

    /**
     * @inheritdoc
     * @return StreamsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StreamsQuery(get_called_class());
    }
	
	
	public function checkDuplicate($attribute, $params)
	{
		$sql = "SELECT * FROM streams where stream = '" . $this->stream ."' and year =  ". $this->year ." limit 1";
		$check = Yii::$app->db->createCommand($sql)->queryOne();
		if ( $check['id']  ){
			$this->addError($attribute, "Duplicate - The stream (".$this->stream.") for the year " .$this->year ."already has been indicated as playing that game");
		}	
	}
	
}