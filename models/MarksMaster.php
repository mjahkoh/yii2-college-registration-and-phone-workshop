<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use Yii;
use app\helpers\Setup;
use app\models\Members;
/**
 * This is the model class for table "marks_master".
 *
 * @property integer $id
 * @property integer $members_id
 * @property string $date_of_exam
 * @property integer $unit
 * @property integer $total_marks
 *
 * @property MarksDetail[] $marksDetails
 */
class MarksMaster extends \yii\db\ActiveRecord
{

		const FIRSTYEAR 		= 1;
		const SECONDYEAR 		= 2;
		const THIRDYEAR 		= 3;
		const FOURTHYEAR 		= 4;
        public $classes = [
            self::FIRSTYEAR     		=> 'First Year',
            self::SECONDYEAR 			=> 'Second Year',
			self::THIRDYEAR 			=> 'Third Year',
			self::FOURTHYEAR 			=> 'Fourth Year',
        ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'marks_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['members_id', 'unit', 'total_marks', 'class'], 'integer'],
			[['members_id', 'unit', 'total_marks', 'class'], 'required'],
            [['date_of_exam'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'members_id' => Yii::t('app', 'Lecturer'),
            'date_of_exam' => Yii::t('app', 'Date Of Exam'),
            'class' => Yii::t('app', 'Class'),
            'total_marks' => Yii::t('app', 'Total Marks'),
            'unitsName' => Yii::t('app', 'Unit'),
			'fullName' => Yii::t('app', 'Lecturer'),
        ];
    }

    public function getMembers()
    {
        return $this->hasOne(Members::className(), ['id' => 'members_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarksDetails()
    {
        return $this->hasMany(MarksDetail::className(), ['marks_master_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return MarksMasterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MarksMasterQuery(get_called_class());
    }
	
	public function getUnits()
	{
		return $this->hasOne(Units::className(),['id'=>'unit']);
	}
	
	/* Getter for person full name */
	public function getUnitsName() {
		return $this->units->unit ;
	}
	
	/*
	before save can be used for instance in formatting dates before saving.
	*/
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			// ...custom code here...
			$this->date_of_exam = Yii::$app->formatter->asDate($this->date_of_exam,'yyyy-MM-dd HH:mm:ss');
			return true;
		} else {
			return false;
		}
	}

	//we are saving 
	public function afterSave($insert, $changedAttributes )
	{
		parent::afterSave($insert, $changedAttributes );

		$sql = "SELECT members.id FROM members INNER JOIN units_booked_by_students ON members.id = units_booked_by_students.student_id
		where unit_id = " . $this->unit ;
		$models = Yii::$app->db->createCommand($sql)->queryAll();
		//echo "sql $sql<br>";exit;
		if ($models){
			$query = [];
			foreach ($models as $index => $model) {
				// populate and save records for each model
				$studentid = $model['id'];
				$marksmasterid = $this->id;
				$query[] = "INSERT INTO marks_detail (student_id,marks_master_id) VALUES($studentid, $marksmasterid)";
				echo "INSERT INTO marks_detail (student_id,marks_master_id) VALUES($studentid, $marksmasterid)<br>";
			}
			Setup::executeQuery($query);			
		}
		//exit;
		if ($insert) {
			Yii::$app->session->setFlash('success', "The model successfully added");
		} else {
			Yii::$app->session->setFlash('success', "The model was successfully updated");
		}
		
		return true;
	}
	
    /** @inheritdoc */
    public function beforeValidate()
    {
		//getIdentity Yii::$app->user->identity->getId()
		//echo Yii::$app->user->identity->id; exit;
		if (parent::beforeValidate()){
			//$this->members_id = Yii::$app->user->identity->id;
			$this->date_of_exam = Yii::$app->formatter->asDate($this->date_of_exam,'yyyy-MM-dd');
			return true;
		}
		
		return true;	
	}
	
}
