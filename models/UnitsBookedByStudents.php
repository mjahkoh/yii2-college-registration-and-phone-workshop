<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "units_booked_by_students".
 *
 * @property integer $id
 * @property integer $unit_id
 * @property integer $semester
 * @property string $academic_year
 * @property integer $student_id
 *
 * @property Units $unit
 */
class UnitsBookedByStudents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'units_booked_by_students';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unit_id', 'semester', 'student_id'], 'integer'],
			[['unit_id', 'semester', 'student_id', 'academic_year'], 'required'],
            [['academic_year'], 'safe'],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Units::className(), 'targetAttribute' => ['unit_id' => 'id']],
			['unit_id', 'checkDuplicate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Student'),
            'unit_id' => Yii::t('app', 'Unit'),
            'semester' => Yii::t('app', 'Semester'),
            'academic_year' => Yii::t('app', 'Year'),
            'student_id' => Yii::t('app', 'Student'),
			'unitName' => Yii :: t ( 'app' , 'Unit Name' ) ,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnits()
    {
        return $this->hasOne(Units::className(), ['id' => 'unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasOne(Members::className(), ['id' => 'student_id']);
    }

	/*Getter for country name */
	public function getUnitName() {
		return $this->units->unit ;
	}


    /**
     * @inheritdoc
     * @return UnitsBookedByStudentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UnitsBookedByStudentsQuery(get_called_class());
    }

    public function checkDuplicate($attribute)
    {
	
		if ($this->isNewRecord) {
			$model = UnitsBookedByStudents::find()
			->where(['student_id' => $this->student_id,])
			->andwhere(['=','semester',$this->semester])
			->andwhere(['=','academic_year',$this->academic_year])
			->andwhere(['=','unit_id',$this->unit_id])->limit(1)->one();
		} else {
			$model = UnitsBookedByStudents::find()
			->where(['student_id' => $this->student_id,])
			->andwhere(['=','semester',$this->semester])
			->andwhere(['=','academic_year',$this->academic_year])
			->andwhere(['=','unit_id',$this->unit_id])
			->andwhere(['<>','id',$this->id])->limit(1)->one()
			;
		}  
	
		//check wether its greater than today or less than 18 years
		if ($model != false) {
			$this->addError($attribute, 'There is a duplicate unit in that year');
		}

	}

}
