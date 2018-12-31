<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "events".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $date_created
 */
class Events extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'title'], 'required'],
            [['id'], 'integer'],
            [['description'], 'string'],
            [['date_created'], 'safe'],
            [['title'], 'string', 'max' => 30],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'date_created' => 'Created Date',
        ];
    }

    /**
     * {@inheritdoc}
     * @return EventsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventsQuery(get_called_class());
    }
	
	/*
	before save can be used for instance in formatting dates before saving.
	*/
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			// ...custom code here...
			$this->date_created = Yii::$app->formatter->asDate($this->date_created,'yyyy-MM-dd HH:mm:ss');
			return true;
		} else {
			return false;
		}
	}
	
}
