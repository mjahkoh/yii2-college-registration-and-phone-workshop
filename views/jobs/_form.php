<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\PhoneMakes;
use app\models\PhoneModels;
use app\models\JobsMembers;
use app\models\Jobs;
use app\models\Settings;
use app\models\Countrys;
use kartik\date\DatePicker;
use yii\widgets\DetailView;
use yii\helpers\Url ;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Jobs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jobs-form">

    <?php 
	/*DetailView::widget([
        'model' => $payments,
        'attributes' => [
            //'id',
            [
				'label'		=> "Total Payments",
				'attribute'	=>'amount',
				'value' 	=> function ($model) {
					return (number_format($model->amount, 2));
				}
			],	
        ],
    ]) */?>
	<?php 
		$members = New JobsMembers;
	?>
    <?php $form = ActiveForm::begin(['id'=> 'jobs-'. time()]); ?>
	<?php
		$defaultvalue = Settings::getDefaultCountryPrefix();
		
	
	if ($model->isNewRecord) {
		$prompt = 'Choose Clientelle';
		$model->newclient = true ;
		//echo $form->field($model, 'newclient')->checkbox(['value' => $newclient]); 
		//echo Html::checkbox( 'newclient', true, 'value'=>true));
		echo Html::checkbox('newclient', $checked = true, ['uncheck'=> NULL, 'label'=> 'New Client' ]); 
		//echo $form->field($model, 'newclient')->checkbox(['checked'=>true,'uncheck'=>'0','value'=>true]); 
		?>
	
	
<?php $telprefixes = ArrayHelper:: map (Countrys::find()->orderBy('country ASC')-> all() , 'international_tel_code' , 'international_tel_code' ) ;?>
	<div style="width: 700px;">
	 <div style="float: left; width: 400px;"><?= $form->field($model, 'name')->textInput() ?> </div>
	 <div style="float: left; width: 100px;"><?= $form->field($model, 'telprefix')->dropDownList(
		 $telprefixes,
			[
				'value' => $defaultvalue
			]
		) ; ?>
	 </div>
	 <div style="float: left; width: 200px;"><?= $form->field($model, 'tel')->textInput() ?></div>
	 <br style="clear: left;" />
	</div>
	
	<?php
	} else {
		$prompt = NULL;
	}
	$clients = ArrayHelper:: map (JobsMembers::find()->orderBy('name ASC')-> all(),'id','name' ) ;
	echo $form->field($model,'client_id')->dropDownList( $clients ,
			[
				'prompt' => $prompt ,
						
			]) ; 

	?>

<?php	
	/*this gives you the Phone make plus model eg Nokia 2210*/
/*
	$phonemodels = ArrayHelper:: map (PhoneModels::find()
	->select([ 
			"concat(phone_makes.make, ' ', phone_models.model) as model",
			'phone_models.id'
			])
	->joinWith('phoneMakes')
	->orderBy('make, model ASC') 
	->all() , 'id' , 'model' ) ;
	
    $phonemodels = ArrayHelper:: map (PhoneModels::find()->orderBy('model ASC')-> all() , 'id' , 'model' ) ;
	echo $form->field($model,'phone_model_id')
				->widget(Select2::classname(), [
						'id'=> 'jobs-phone_model_id',
						'data'			=>	$phonemodels,
						'language'		=>	'en',
						'options'		=>	['placeholder'=> 'select Phone Model'],
						'pluginOptions'	=> [
							'allowClear'=> true
						],
		]);	
*/
	
	/* phone makes drop down */
	$phonemakes = ArrayHelper:: map (PhoneMakes::find()->orderBy('make ASC')-> all(),'id','make' ) ;
	echo $form->field($model,'phone_make_id')->dropDownList( $phonemakes ,
			[
				'prompt' => 'Choose Make',
						
			]) ; 

	//populate citys
	
	//if posted
	if (Yii::$app->request->post()) {
		$request = Yii::$app->request->post();
		$selected_make = $request['Jobs']['phone_make_id'];
		$selected_model = $request['Jobs']['phone_model_id'];
	} else {
		$selected_make = $model->phone_make_id;
		$selected_model = $model->phone_model_id;
	}

	/* phone models drop down */
	$phonemodels = ArrayHelper:: map (PhoneModels::find()->where(['phone_make_id' => $selected_make])
	->orderBy('model ASC')-> all(),'id','model' ) ;
	echo $form->field($model,'phone_model_id')->dropDownList( $phonemodels ,
			[
				'prompt' => 'Choose Model',
						
			]) ; 


?>

    <?= $form->field($model, 'problem')->textarea(['maxlength' => true]) ?>

<?php $currency = ArrayHelper:: map (Countrys::find()->orderBy('country ASC')-> all() , 'international_tel_code' , 'currency' ) ;?>

	<div style="width: 300px;">
	 <div style="float: left; width: 100px;">
		<?php 
		echo $form->field($model,'currency')->dropDownList(
						$currency ,
						[
							'value' => $defaultvalue
						]) ; 
		?>
	 </div>
	 <div style="float: left; width: 200px;">
	 <?= $form->field($model, 'charges',
			[
				'inputOptions'=>['placeholder'=>'0', 'value'=> $model->isNewRecord ? 0 : $model->charges],
			]
	 )->textInput() ?> 
	 </div>
	 <br style="clear: left;" />
	</div>



	<?php 
			if ($model->isNewRecord) {
				$model->date_job_commenced = date('d-M-Y', strtotime('0 days'));//d-M-Y
			}
			echo $form->field($model, 'date_job_commenced')->widget
			(DatePicker::classname(), [
				'options' => ['placeholder' => 'Job Start Date'],
				//'value' => '01-Feb-1996',
				//'value' => date('d-M-Y', strtotime('+2 days')),
				'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'dd-M-yyyy'//dd-M-yyyy
				]
			]);/**/
	?>

	<?php 
			if ($model->isNewRecord) {
				$model->date_job_completed = date('d-M-Y', strtotime('0 days'));//d-M-Y
			}
			echo $form->field($model, 'date_job_completed')->widget
			(DatePicker::classname(), [
				'options' => ['placeholder' => 'Job Stop Date'],
				//'value' => '01-Feb-1996',
				//'value' => date('d-M-Y', strtotime('+2 days')),
				'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'dd-M-yyyy'//dd-M-yyyy
				]
			]);/**/
	?>

<?php	
	$membersallocated = ArrayHelper:: map (JobsMembers::find()
			->where(['category' => JobsMembers::STAFF_MEMBER])
			->orderBy('name ASC')-> all(),'id','name' ) ;
	echo $form->field($model,'staff_allocated_id')->dropDownList(
						$membersallocated ,
						[
							'prompt' => 'Choose Staff Member',
							
						]) ; 
?>
  <?php 
	echo Html::activeHiddenInput($model, 'companyname', ['value'=> $company['company_name']]); 
	echo Html::activeHiddenInput($model, 'companyphysicallocation', ['value'=> $company['physical_location']]); 
	echo Html::activeHiddenInput($model, 'companymobileprefix', ['value'=> $company['mobile_prefix']]); 
	echo Html::activeHiddenInput($model, 'companymobile', ['value'=> $company['mobile']]); 
	echo Html::activeHiddenInput($model, 'companytelprefix', ['value'=> $company['tel_prefix']]); 
	echo Html::activeHiddenInput($model, 'companytel', ['value'=> $company['tel']]); 


	if ($model->isNewRecord) {
		$model->status = Jobs::STATUS_IN_COMPLETE;//d-M-Y
	} else {
	 	echo Html::activeHiddenInput($model, 'telprefix', ['value'=> $model->jobsMembers->tel_prefix]); 
		echo Html::activeHiddenInput($model, 'newclient', ['value'=> 0]); 
		echo Html::activeHiddenInput($model, 'tel', ['value'=> $model->jobsMembers->tel]); 
	}
  	echo $form->field($model, 'status')->radioList(['10' => 'In-complete', '1' => 'Complete', '0' => 'Unrepairable']);    
	?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$isNewRecord = $model->isNewRecord ? 1 : 0 ;
$host =  Url::toRoute(["/general/get-options"]);
$unsentsmshost = "'" . Url::toRoute(['/index.php/jobs/get-unsent-sms']) . "'";
$smssettings = "'" . Url::toRoute(["/index.php/jobs/get-sms-settings"]) . "'";
$setsmsschedule = "'" . Url::toRoute(["/index.php/jobs/set-sms-schedule"]) . "'";
$setsmssentstatus = "'" . Url::toRoute(["/index.php/jobs/set-sms-sent-status"]) . "'";
$varposteddata = json_encode(!empty($_POST) ? $_POST : array());	
//print_r($_POST);
$posted = ($_POST) ? 1 : 0;
$newclient = 0;
if ($_POST && isset($_POST['newclient']) && $_POST['newclient'] == 1) {
	$newclient = $_POST['newclient'];
} 

if ($selected_model == null) {
	$selected_model = 0;
} 

$phone_model_id = $model->phone_model_id == null ? 0 : $model->phone_model_id;

$this->registerJs(
"

$(document). ready (function (){

	var isNewRecord = $isNewRecord;
	var phonemodel;
	var phoneowner;
	var problem;
	var currency;
	var charges;
	var currency;
	var message = null;
	var unsentsmshost = $unsentsmshost;
	var smssettings = $smssettings;
	var setsmsschedule = $setsmsschedule;
	var posted = $posted;
	var newclient = $newclient;
	var models = null;
	var setsmssentstatus = $setsmssentstatus;
	
		//we use jquery to select the options in the county and city dropdowns
		$('#jobs-phone_model_id').val($selected_model);
	
		$('select').on('change', function(e){//, 'load',
			console.log('changed');
			var host = '$host';
			var formName = 'jobs-form';
			var phone_model_id = '$phone_model_id';
			if(this.id === 'jobs-phone_make_id'){ 	//&& isNewRecord != 1
				var dataObj = {'dropdown':this.id, 'formName':formName, 'id':this.value, 'host':host, 'nxtElemIndex':phone_model_id};
				dynoDropdowns(this, '#jobs-phone_model_id', dataObj);
			} 
			
		});
	

	
	/*
		on entry if its new record 
		=> check new client and show name and tel
		=> hide client_id
		//if its posted dont intefere with the visibility og the name, tel & telprefix and client_id
	*/
	if (isNewRecord && $posted == false) {//  not have been posted
		$('#jobs-name').parent().show();
		$('#jobs-tel').parent().show();
		$('#jobs-telprefix').parent().show();
		
		$('#jobs-client_id').parent().hide();
		$('#jobs-newclient' ).prop( 'checked', true );
	}	
	
	//if its posted check the newclientid. if its set its new
	if (isNewRecord && $posted == true && $newclient == true ) {//
		$('#jobs-name').parent().show();
		$('#jobs-tel').parent().show();
		$('#jobs-telprefix').parent().show();
		
		$('#jobs-client_id').parent().hide();
		$('#jobs-newclient' ).prop( 'checked', true );
	}	else if (isNewRecord && $posted == true  && $newclient == false ) {
		$('#jobs-name').parent().hide();
		$('#jobs-tel').parent().hide();
		$('#jobs-telprefix').parent().hide();
		
		$('#jobs-client_id').parent().show();
		$('#jobs-newclient' ).prop( 'checked', false );
	}
	
	
	/* hide show */						
	//$('[name='newclient']').change(function(){
	$('[name=newclient]').change(function(){
	//$('.newclient').change(function(){
		console.log('value selected: ' + $('[name=newclient]').val());			// $('[name=newclient]')[0].checked)
		//if ($('#newclient' ).prop('checked') == true ) { //if selected new client , hide client_id and reveal the rest display: none
		if ($('[name=newclient]').prop('checked') == true ) { //if selected new client , hide client_id and reveal the rest display: none
			console.log('checked');
			$('#jobs-client_id').parent().hide(); 
			$('#jobs-name').parent().show(); 
			$('#jobs-tel').parent().show();
			$('#jobs-telprefix').parent().show(); 
		} else {
			console.log('unchecked');
			$('#jobs-client_id').parent().show(); 
			$('#jobs-name').parent().hide();  
			$('#jobs-tel').parent().hide();  
			$('#jobs-telprefix').parent().hide(); 
		}
		if (isNewRecord) {
			//$('#jobs-name').parent().toggle(); 
			//$('#jobs-tel').parent().toggle();
			//$('#jobs-client_id').parent().toggle();
		}
		
	});		


	
	
});

"
);

?>