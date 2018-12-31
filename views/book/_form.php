<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;

use app\models\Authors;
/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>
    
	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	
<?php
	$authors = ArrayHelper:: map (Authors::find() 
	->orderBy('name ASC')-> all() , 'author_id' , 'name' ) ;


	echo $form->field($model,'author_id')
			->widget(Select2::classname(), [
					'data'			=>	$authors,
					'language'		=>	'en',
					//'disabled'		=>	TRUE,
					'options'		=>	['placeholder'=> 'select Author'],
					'pluginOptions'	=> [
						'allowClear'=> true
					],
					//'value'		=>	[1],
	]);				
?>
    <?= $form->field($model, 'book_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'synopsis')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'color')->dropDownList([
	""=> "Select one"     , 
	"Black" => "Black", 
	"Blue" => "Blue", 
	"Brown" => "Brown",
	"Cyan" => "Cyan", 
	"Green" => "Green",
	"Orange" => "Orange", 
	"Pink" => "Pink",
	"Red" => "Red",
	], ['size' => 1]) ?>

	<?php
			//working model of a date
			//$model->reading_date = date('M-Y', strtotime('-1 days'));//d-M-Y
			echo $form->field($model, 'publish_date')->widget
			(DatePicker::classname(), [
				'options' => ['placeholder' => 'Publish Date'],
				//'value' => '01-Feb-1996',
				//'value' => date('d-M-Y', strtotime('+2 days')),
				'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'd-M-yyyy'//dd-M-yyyy
				]
			])->label('Publish Date');/**/
	?>

    <?= $form->field($model, 'sell_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buy_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([""=> "Select one"     , 1 => "Active", 2 => "Dormant"], ['size' => 1]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
