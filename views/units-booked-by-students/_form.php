<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Units;
use app\models\Members;
use yii\widgets\Pjax;
use kartik\builder\TabularForm;
use kartik\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\UnitsBookedByStudents */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="units-booked-by-students-form">

    <?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'unit_id')->dropDownList(ArrayHelper::map(Units::find()->orderBy('unit ASC')->all(), 'id', 'unit'), ['prompt'=>'-Choose your Unit-']);?>

    <?php echo $form->field($model, 'semester')->dropDownList(['1' => 'I', '2' => 'II', '3' => 'III'], ['prompt'=>'-Choose a Semester-']) ?>

    <?php echo $form->field($model, 'academic_year')->dropDownList(['2017' => '2017'], ['prompt'=>'-Choose an Year-']) ?>

    <?php 
	/* student id will be retrived from the logged in id and locked*/
echo $form->field($model, 'student_id')->dropDownList(ArrayHelper::map(Members::find()->orderBy('firstname, middlename, surname ASC')->all(), 'id',
                    function($model, $defaultValue) {
                        return $model['firstname'] .' ' .$model['middlename'] . ' ' .$model['surname'];
                    }
					 ), ['prompt'=>'-Choose Student-']);
					 
?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php

//only venture here after the initial saving
if (!$model->isNewRecord) {
	Pjax::begin(['id'=>'indexExample4Pjax']); 
	echo  Html::beginForm([''], 'post', ['data-pjax' => '1', 'id' => 'indexExample4Formarray']);
	echo TabularForm::widget([
		// your data provider
		'dataProvider'=>$dataProviderArraynewDp,
		//'id' => 'kv-grid-tableID',
	
		// formName is mandatory for non active forms
		// you can get all attributes in your controller 
		// using $_POST['kvTabForm']
		'formName'=>'kvTabForm',
		
		// set defaults for rendering your attributes
		'attributeDefaults'=>[
			'type'=>TabularForm::INPUT_TEXT,
		],
		
		// configure attributes to display
		'attributes'=>[
			'id'=>['label'=>'book_id', 'type'=>TabularForm::INPUT_HIDDEN_STATIC,'columnOptions'=>['width'=>'3px']],
			//'name'=>['label'=>'Book Name'],
			'author_id'=>[
					'label'=>'Book Name',
					'type'=>TabularForm::INPUT_DROPDOWN_LIST, 
					'items'=>ArrayHelper::map(Authors::find()->orderBy('name')->asArray()->all(), 'author_id', 'name'),
					'columnOptions'=>['width'=>'185px']
			],
			'publish_date'=>[
				'label'=>'Published On', 
				'type'=>TabularForm::INPUT_WIDGET,
				'widgetClass'=>\kartik\widgets\DatePicker::classname(), 
					'options'=> function($model, $key, $index, $widget) {
						return ($key % 2 === 0) ? [] :
						[ 
							'pluginOptions'=>[
								'format'=>'yyyy-mm-dd',
								'todayHighlight'=>true, 
								'autoclose'=>true
							]
						];
					},
					'columnOptions'=>['width'=>'170px']
			],
			
			
			/*
				'publish_date'=>[
					'type' => function($model, $key, $index, $widget) {
						//return ($key % 2 === 0) ? TabularForm::INPUT_HIDDEN : TabularForm::INPUT_WIDGET;
						return TabularForm::INPUT_WIDGET;
					},
					'widgetClass'=>\kartik\widgets\DatePicker::classname(), 
					'options'=> function($model, $key, $index, $widget) {
						return ($key % 2 === 0) ? [] :
						[ 
							'pluginOptions'=>[
								'format'=>'yyyy-mm-dd',
								'todayHighlight'=>true, 
								'autoclose'=>true
							]
						];
					},
					'columnOptions'=>['width'=>'170px']
				],
			*/
			
			
		],
		
		// configure other gridview settings
		'gridSettings'=>[
			'panel'=>[
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Manage Books</h3>',
				'type'=>GridView::TYPE_PRIMARY,
				'before'=>false,
				'footer'=>false,
				'after'=>Html::button('<i class="glyphicon glyphicon-plus"></i> Add New', ['id'=>'addNew', 'type'=>'button', 'class'=>'btn btn-success kv-batch-create']) . ' ' . 
						Html::button('<i class="glyphicon glyphicon-remove"></i> Delete', ['type'=>'button', 'class'=>'btn btn-danger kv-batch-delete']) . ' ' .
						Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Save', ['type'=>'button', 'class'=>'btn btn-primary kv-batch-save'])
			]
		]
	]);
	echo Html::endForm();
	Pjax::end(); 
}

?>


</div>
