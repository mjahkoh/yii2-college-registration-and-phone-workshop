<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "games".
 *
 * @property integer $id
 * @property string $game
 *
 * @property StudentGames[] $studentGames
 */
class Games extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'games';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['game'], 'required'],
			[['game'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'game' => Yii::t('app', 'Game'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentGames()
    {
        return $this->hasMany(StudentGames::className(), ['gamesid' => 'id']);
    }

    /**
     * @inheritdoc
     * @return GamesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GamesQuery(get_called_class());
    }
}
