<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url ;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\payments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payments-form">

    <?php $form = ActiveForm::begin([
		'id'=>'payments-form',
		'options' => ['data-pjax' => true],
		'enableAjaxValidation' => false,
	]); ?>

    <?= Html::activeHiddenInput($model, 'job_id', ['value'=> $job]); ?>

    <?= $form->field($model, 'amount')->textInput() ?>
	
	<?php
			//working model of a date
			//$model->reading_date = date('M-Y', strtotime('-1 days'));//d-M-Y
			if ($model->isNewRecord) {
				$model->date_of_payment = date('d-M-Y',strtotime("now"));//d-M-Y
			}
			echo $form->field($model, 'date_of_payment')->widget
			(DatePicker::classname(), [
				'options' => ['placeholder' => 'Date of payment'],
				//'value' => '01-Feb-1996',
				//'value' => date('d-M-Y', strtotime('+2 days')),
				'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'd-M-yyyy'//dd-M-yyyy
				]
			]);/**/
	?>
	
	
	<?php //Html::activeHiddenInput($model, 'charges'); ?>
	<?php //Html::activeHiddenInput($model, 'newclient'); ?>
	<?php //Html::activeHiddenInput($model, 'newclient'); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php


$this->registerJs(
"
$(document). ready (function (){

	console.log('payments-form');

<!-- //start before submit-->
	$('#payments-form').on('beforeSubmit', function(event, jqXHR, settings) {
			event.preventDefault();
			event.stopImmediatePropagation();
			///alert('works');
			console.log('on click');
			var form = $(this);
			if(form.find('.has-error').length) {
					console.log('form errors');
					return false;
			}
			id = $(this).attr('data-key');
			console.dir(id);
			//check wether they are previous readings not entered checkPreviousReading
			//we need to send reading_date * account_no
			jQuery.ajax({
					url			: '" . Url::toRoute(["payments/create?job=id"]). "',
					type		: 'POST',
					data		: form.serialize(),
					dataType	:'json', 
					success: function(response) {
						//lets clear the amount textbox
						$('#payments-amount').val('');
						
						title = 'Make Payments - Job ID: ' + id + ' - charges: ' + formatNumbers(charges,2);
        				/*
						document.getElementById('modalHeader').innerHTML = \"<button type='button' class='close'\" +
                                                                \"data-dismiss='modal' aria-label='Close'>\" +
                                                                \"<span aria-hidden='true'>&times;</span>\" +
                                                           \"</button>\" +
                                                           '<h4>' + title + '</h4>';		
					   */												   
							console.log(response);
							$('#modal').modal({show:true});
							//$.pjax.reload({container:'#jobsGrid'});
					}
			})
			
			//.done (function() {alert('success');})
			.fail (function(xhr, status, errorThrown) {
				alert('sorry problem heres');
				console.log('Error: ' + errorThrown);
				console.log('Status' + status);
				console.dir(xhr);
			});
			
			
			return false;
	});
	//end submit

});
"
);
?>
