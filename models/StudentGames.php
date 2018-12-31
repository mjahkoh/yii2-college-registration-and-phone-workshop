<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "student_games".
 *
 * @property integer $id
 * @property integer $gamesid
 * @property integer $studentid
 *
 * @property Students $student
 * @property Games $games
 */
class StudentGames extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_games';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gamesid', 'studentid'], 'required'],
			[['gamesid', 'studentid'], 'integer'],
			[['studentid'], 'checkDuplicate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'gamesid' => Yii::t('app', 'Gamesid'),
            'studentid' => Yii::t('app', 'Student'),
			'fullName' => Yii::t('app', 'Member'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasOne(Members::className(), ['id' => 'studentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGames()
    {
        return $this->hasOne(Games::className(), ['id' => 'gamesid']);
    }

    /**
     * @inheritdoc
     * @return StudentGamesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StudentGamesQuery(get_called_class());
    }
	
	/**/
	public function checkDuplicate($attribute, $params)
	{
		$sql = "SELECT * FROM student_games where studentid =  ". $this->studentid ." limit 1";
		$check = Yii::$app->db->createCommand($sql)->queryOne();
		if ( $check['id']  ){
			$this->addError($attribute, "Duplicate - The student already has been indicated as playing that game");
		}	
	}
	
}
