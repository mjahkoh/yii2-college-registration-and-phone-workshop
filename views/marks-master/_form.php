<?php
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\helpers\Url ;
use kartik\grid\GridView;
use kartik\grid\EditableColumn;
use kartik\editable\Editable;
use kartik\widgets\ColorInput;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;
use kartik\widgets\DepDrop;
use kartik\date\DatePicker;
use kartik\builder\TabularForm;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;

use app\models\Units;
use app\models\Authors;
use app\models\Streams;
use app\models\Members;
/* @var $this yii\web\View */
/* @var $model app\models\MarksMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="marks-master-form">
<?php Pjax::begin(); ?>
    <?php $form = ActiveForm::begin(['id' => 'marksMaster',]); ?>

    <?php //$form->field($model, 'members_id')->textInput() ?>

    <?php $form->field($model, 'date_of_exam')->textInput() ?>
		<?php 	
			//$model->date_of_exam = date('d-M-Y', strtotime($model->date_of_exam));//d-M-Y
			echo $form->field($model, 'date_of_exam')->widget
			(DatePicker::classname(), [
				'options' => ['placeholder' => 'Date of Exam'],
				'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'dd-M-yyyy'
				]
			]);
		?>


<?php //$form->field($model, 'unit')->dropDownList(ArrayHelper::map(Units::find()->orderBy('unit ASC')->all(), 'id', 'unit'), ['prompt'=>'-Choose your Unit-']);?>

<?php
	$units = ArrayHelper:: map (Units::find()->orderBy('unit ASC')-> all() , 'id' , 'unit' ) ;
	echo $form->field($model,'unit')->dropDownList(
					$units,
					[
						'prompt' => 'Choose your Unit',
						'onChange' => '
							$.post("' . Url::toRoute(["units-booked-by-students/students-list?id="]) . '" + $(this).val(), function (data){
								$("#marksmaster-students").html(data);
								$("#marksmaster-students-header").html("<b>Students Undertaking " + $("#marksmaster-unit option:selected").text() + "<b>");
							});'
					]) ; 
?>
<?= $form->field($model, 'total_marks')->textInput() ?>
	
<?= $form->field($model, 'class')-> dropDownList(
			$model->classes ,
			array('prompt'=>'Select')
			); 
			

//populate members
$members = ArrayHelper:: map (Members::find()->orderBy('firstname, middlename, surname ASC')-> all() , 'id' , 'fullName' ) ;
echo $form->field($model,'members_id')->dropDownList(
					$members ,
					[
						'prompt' => 'Choose Member',
						
					]) ; 
?>
	
<div id="marksmaster-students-header"></div>
<div id="marksmaster-students"></div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php
/*
if this is a new record, dont show the tabluar Form as the details are not saved
*/
if (!$model->isNewRecord) {
	$attribs = $searchModel->formAttribs;
	echo TabularForm::widget([
		'dataProvider'=>$dataProvider,
		'form'=>$form,
		'attributes'=>$attribs,
		'gridSettings'=>[
			'floatHeader'=>true,
			'panel'=>[
				'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Manage Score</h3>',
				'type' => GridView::TYPE_PRIMARY,
				'after'=> Html::a('<i class="glyphicon glyphicon-plus"></i> Add New', '#', ['class'=>'btn btn-success']) . ' ' . 
						Html::a('<i class="glyphicon glyphicon-remove"></i> Delete', '#', ['class'=>'btn btn-danger']) . ' ' .
						Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Save', ['class'=>'btn btn-primary'])
			]
		]	
	]);
}

// Add other fields if needed or render your submit button
echo '<div class="text-right">' . 
     Html::submitButton('Submit', ['class'=>'btn btn-primary']) .
     '<div>';

?>

	<?php ActiveForm::end(); ?>
	

<?php Pjax::end(); ?>

</div>
