<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "authors".
 *
 * @property integer $author_id
 * @property string $name
 * @property integer $status
 *
 * @property Book[] $books
 */
class Authors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'authors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'required'],
			[['status'], 'integer'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'author_id' => Yii::t('app', 'Author ID'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Book::className(), ['author_id' => 'author_id']);
    }

    /**
     * @inheritdoc
     * @return AuthorsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuthorsQuery(get_called_class());
    }
}
