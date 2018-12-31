<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\Companies;
use yii\helpers\ArrayHelper;

	
	$companies = ArrayHelper:: map (Companies::find()->orderBy('company_name ASC')-> all() , 'id' , 'company_name' ) ;
	/*	this dropDownList works
	echo $form->field($model,'companies_company_id')->dropDownList(
					$companies ,
					[
						'prompt' => 'Choose Company',
					]) ; 
	*/
?>


<?= $form->field($model,'companies_company_id')->widget(Select2::classname(), [
					'data'			=>	$companies,
					'language'		=>	'en',
					'options'		=>	['placeholder'=> 'select company'],
					'pluginOptions'	=> [
						'allowClear'=> true
					],
	]);				
?>

    <?= $form->field($model, 'branch_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

		<?php
			//working model of a date
			$model->date_created = date('M-Y', strtotime('-1 days'));//d-M-Y
			//$model->date_created = date('d-M-Y',strtotime($model->date_created));//d-M-Y
			echo $form->field($model, 'date_created')->widget
			(DatePicker::classname(), [
				'options' => ['placeholder' => 'Inception Date'],
				//'value' => '01-Feb-1996',
				//'value' => date('d-M-Y', strtotime('+2 days')),
				'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'd-M-yyyy'//dd-M-yyyy
				]
			]);/**/
		?>

    <?= $form->field($model, 'status')->dropDownList([0 => "Active", 1 => "Dormant"], ['size' => 1]) ?>

    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>