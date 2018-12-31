<?php

namespace app\models;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use yii\helpers\Html;
use Yii;

/**
 * This is the model class for table "marks_detail".
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $marks
 * @property integer $marks_master_id
 * @property integer $rating
 * @property string $last_date_of_exam
 *
 * @property MarksMaster $marksMaster
 */
class MarksDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'marks_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'marks', 'marks_master_id', 'rating'], 'required'],
			[['student_id', 'marks', 'marks_master_id', 'rating'], 'integer'],
            [['last_date_of_exam'], 'safe'],
            [['marks_master_id'], 'exist', 'skipOnError' => true, 'targetClass' => MarksMaster::className(), 'targetAttribute' => ['marks_master_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'marks' => Yii::t('app', 'Marks'),
            'marks_master_id' => Yii::t('app', 'Marks Master ID'),
            'rating' => Yii::t('app', 'Rating'),
            'last_date_of_exam' => Yii::t('app', 'Last Date Of Exam'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMarksMaster()
    {
        return $this->hasOne(MarksMaster::className(), ['id' => 'marks_master_id']);
    }

    /**
     * @inheritdoc
     * @return MarksDetailQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MarksDetailQuery(get_called_class());
    }
	
	/**/
	public function getMembers()
	{
		return $this->hasOne(Members::className(),['id'=>'student_id']);
	}
	
	
	/*example 1 start*/
	public function getFormAttribs() {
		$common = 	[
		
						'id'	=>[
								'label'=>'student_id',
								'type'=>TabularForm::INPUT_HIDDEN_STATIC,
								'columnOptions'=>['width'=>'3px','hidden'=>true],
								'value' => function ($model, $key, $index, $widget) {
									return Html::a($model->id, ['/members/view','id' =>$model->id]);
								}			
								],
						
						'fullName'=>[
							'type'=>TabularForm::INPUT_STATIC, 
							'label'=>'Student',
							'columnOptions'=>['hAlign'=>GridView::ALIGN_LEFT, 'width'=>'100px'],
							'value'=>function($model, $key, $index, $widget) {
								//return ($key % 2 === 0) ? TabularForm::INPUT_HIDDEN : TabularForm::INPUT_WIDGET;
								return ($model->members->fullName);
							},
						],
			];

		// in update mode use INPUT_STATIC all over 
		if (Yii::$app->controller->action->id === 'update') {
		
			$common['marks'] = [		
					'type'=>TabularForm::INPUT_TEXT, 
					'label'=>'Marks',
					'options'=>['class'=>'form-control text-right'], 
					'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT, 'width'=>'100px']
			];
				
			$common['rating'] = [
						'label'=>'Rating',
						'type'=>TabularForm::INPUT_DROPDOWN_LIST, 
						'items'=>['1' => 'I', '2' => 'II', '3' => 'III'],
						'columnOptions'=>['width'=>'100px']
			];
				
			$common['last_date_of_exam'] = [
					'label'=>'Last Exam Date', 
					'type'=>TabularForm::INPUT_WIDGET,
					//'format' => ['date', 'php:d-M-Y'],
					'widgetClass'=>\kartik\widgets\DatePicker::classname(), 
						'options'=> function($model, $key, $index, $widget) {
							return ($key % 2 === 0) ? [
								'pluginOptions'=>[
									'format'=>'M-dd-yyyy',
									'todayHighlight'=>true, 
									'autoclose'=>true
								]
							] 
							
							:
							
							[ 
								'pluginOptions'=>[
									'format'=>'dd-M-yyyy',
									'todayHighlight'=>true, 
									'autoclose'=>true
								]
							];
						},
						'columnOptions'=>['width'=>'150px']
			];	
			
		} else {
		
			$common['marks'] = 	[	
					'type'=>TabularForm::INPUT_STATIC, 
					'label'=>'Marks',
					//'options'=>['class'=>'form-control text-right'], 
					'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT, 'width'=>'150px']
			];
			
			$common['rating'] = [		
						'type'=>TabularForm::INPUT_STATIC, 
						'label'=>'Rating',
						//'options'=>['class'=>'form-control text-right'], 
						'columnOptions'=>['width'=>'150px'],
						'value'=>function($model, $key, $index, $widget) {
							 return (
							 ($model->rating == 1) ? "I" :
								(($model->rating == 2) ? "II" : "III")
							 );
						}
			];

			$common['last_date_of_exam'] = [		
					'type'=>TabularForm::INPUT_STATIC,
					'label'=>'Last Exam Date', 
					//'options'=>['class'=>'form-control text-right'], 
					'columnOptions'=>['width'=>'150px'],
					'format' => ['date', 'php:d-M-Y'],
					'value'=>function($model, $key, $index, $widget) {
						return ($model->last_date_of_exam);
					},
			];
			
			
		}
		return $common;
		
	}	

}
