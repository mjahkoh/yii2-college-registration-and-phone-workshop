<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Url ;
use yii\helpers\ArrayHelper;
use kartik\password\PasswordInput;
use kartik\date\DatePicker;

use app\models\States;
use app\models\Countys;
use app\models\Settings;
use app\models\Citys;
use app\models\CountryS;
use app\models\Members;
use app\models\Games;
use app\helpers\Setup;

/* @var $this yii\web\View */
/* @var $model app\models\Members */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="members-form">

    <?php $form = ActiveForm::begin(); //['enableAjaxValidation' => true]?>

<?php echo "<div class='alert alert-success>'" . Yii::$app->session->getFlash('sucess') . "</div>"; ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'middlename')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
	
    <?= $form->field($model, 'nickname')->textInput(['maxlength' => true]) ?>

<?php //$form->field($model, 'sex')->dropDownList(['' => 'Select Your Sex', '1' => 'Male', '2' => 'Female', ]) ?>

<?= $form->field($model, 'sex')->radioList(array('1'=>'Male',2=>'Female')); ?>
<?= $form->field($model, 'marital_status')-> listBox(
			$model->maritalstatus ,
			array('prompt'=>'Select')
			); ?>

<?php 
	$telprefix = ArrayHelper:: map (Countrys::find()->orderBy('country ASC')-> all() , 'international_tel_code' , 'international_tel_code' ) ;
	$defaultvalue = Settings::getDefaultCountryPrefix();
?>


		<div style="width: 100%; overflow: hidden;">
			<div style="width: 300px; float: left;">
			<div style="width: 300px; display: table-cell;"> 
				<?= $form->field($model, 'mobile_prefix')->dropDownList($telprefix,['value' => $defaultvalue]) ?>
			</div>
			<div class="help-block"></div></div>
			<div> <div style="display: table-cell;"> 
				<?= $form->field($model, 'mobile')->textInput(['maxlength' => true]); ?>	
			</div>
			<div class="help-block"></div></div>
		</div>


		<div style="width: 100%; overflow: hidden;">
			<div style="width: 300px; float: left;">
			<div style="width: 300px; display: table-cell;"> 
				<?= $form->field($model, 'home_phone_prefix')->dropDownList($telprefix,['value' => $defaultvalue]) ?>
			</div>
			<div class="help-block"></div></div>
			<div> <div style="display: table-cell;"> 
				<?= $form->field($model, 'home_phone')->textInput(['maxlength' => true]); ?>	
			</div>
			<div class="help-block"></div></div>
		</div>


    
	

    <?php $form->field($model, 'date_of_birth')->textInput() ?>
		<?php 	
			$model->date_of_birth = date('d-M-Y', strtotime($model->date_of_birth));//d-M-Y
			echo $form->field($model, 'date_of_birth')->widget
			(DatePicker::classname(), [
				'options' => ['placeholder' => 'Date of Birth'],
				'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'd-M-yyyy'
				]
			]);
		?>
	
    <?php //$form->field($model, 'password')->textInput(['maxlength' => true]) ?>
<?php	
//password can only be updated in changePassword form
if ($model->isNewRecord) {
    echo $form->field($model, 'username')->textInput(['maxlength' => true]);
	echo $form->field($model, 'password')->widget(PasswordInput::classname(), ['options' => ['placeholder' => Yii::t('app', 'Create password')]]) ;
	echo $form->field($model,'repeatpassword')->textInput(['maxlength' => true])->passwordInput(); 
    echo $form->field($model, 'email')->textInput(['maxlength' => true]) ;
}
?>

<?php 
//populate states
$states = ArrayHelper:: map (States::find()->orderBy('state_name ASC')-> all() , 'state_id' , 'state_name' ) ;
echo $form->field($model,'state')->dropDownList(
		$states ,
		[
			'prompt' => 'Choose your State',
		]) ; 
?>
											
<?php
//populate citys
//$countys = $citys = array();

//if posted
if (Yii::$app->request->post()) {
	$request = Yii::$app->request->post();
	$selected_state = $request['Members']['state'];
	$selected_county = $request['Members']['county'];
	$selected_city = $request['Members']['city'];
} else {
	$selected_state = $model->state;
	$selected_county = $model->county;
	$selected_city = $model->city;
}

//in update mode we have the state , populate the cities based on this condition

$countys = ArrayHelper:: map (Countys::find()->where 
(
	['state_id' => $selected_state])->orderBy('county ASC')->all() , 'id' , 'county' 
) ;

$citys = ArrayHelper:: map (Citys::find()->where 
(
	['county_id' => $selected_county])->orderBy('city ASC')->all() , 'city_id' , 'city' 
) ;
echo $form -> field ( $model , 'county' ) -> dropDownList($countys, ['prompt' => 'Choose your County']) ;
echo $form -> field ( $model , 'city' ) -> dropDownList($citys, ['prompt' => 'Choose your State']) ;
?>
	<?= $form->field($model, 'playgames')->checkbox(); ?>

<?php 
	$category = ($model->isNewRecord) ? $id : $model->category;
	echo Html::activeHiddenInput($model, 'category', ['value'=> $category]); 
?>

<div id="members-form-gridview">

<?php
/*
if new record, checkbox needs to be unchecked 
and derived from the model, otherwise from sql data
*/

Pjax::begin(); 
if ($model->isNewRecord){	
?>
<?= GridView::widget([
       'dataProvider' => $dataProvider,
	   'id' => 'membersGridView',
	   'columns' => [
            //'id',
	           [
	               'label' =>"Game Playes",
	               'attribute' => 'game',
	               'value'=>function($model){
	                   return $model["game"];
	               }
	           ],			
			[
				'class' => 'yii\grid\CheckboxColumn',
					'header' => Html::checkBox('selection_all', false, [
						'class' => 'select-on-check-all',
						'label' => 'Check All',
						]),
				'checkboxOptions' => function ($model, $key, $index, $column){
					return [ 
						"value" => 	 $model->id,
						"checked" => 0
	               
					];
				}
			],
            //['class' => 'yii\grid\ActionColumn'],
        ],
]);

} else {	//update mode
   
   /* if the sudent didnt register any games hide gridview*/
   
   
		echo GridView::widget([
			   'showOnEmpty'=> false,
			   'dataProvider' => $dataProvider,
			   'id' => 'membersGridView',
			   'columns' => [
					'id',
					   [
						   'label' =>"Game Playes",
						   'attribute' => 'game',
						   'value'=>function($data){
							   return $data["game"];
						   }
					   ],			
					[
						'class' => 'yii\grid\CheckboxColumn',
							'header' => Html::checkBox('selection_all', false, [
								'class' => 'select-on-check-all',
								'label' => 'Check All',
								]),
						'checkboxOptions' => function ($data, $key, $index, $column){
							return [ 
								"value" => 	 $data['id'],//	function($data){return $data["id"] ;},//below $data['gamesid']
								"checked" => $data['gamesid'],
						   
							];
						}
					],
					['class' => 'yii\grid\ActionColumn'],
				],
		]);
  
}
Pjax::end(); 
?>

</div>

<?php ////print_r($dataProvider);?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>

<?php
	//$authItems = ArrayHelper::map($authItems, 'name', 'name');
	//print_r($authItems);
	//echo count($authItems);
?>


<?php
/**/
////print_r($dataProvider);
if ($selected_county == null) {
	$selected_county = 0;
	$selected_city = 0;
} 

$isNewRecord = $model->isNewRecord ? 1 : 0 ;
$host =  Url::toRoute(["/general/get-options"]);
$city = $model->city == null ? 0 : $model->city;
$county = $model->county == null ? 0 : $model->county;
$this->registerJs(
"

$(document). ready (function (){
		
		//we use jquery to select the options in the county and city dropdowns
		$('#members-county').val($selected_county);
		$('#members-city').val($selected_city);
		
		//note the dynoDropdowns function is tucked in web/js/main.js
		
		var isNewRecord = $isNewRecord;
					
		/* hide/show gridview with id members-form-gridview*/						
		$('#members-playgames').change(function(){
			$('#members-form-gridview').toggle(); // or do $('#members-form-gridview').parent().toggle();
			
			/*deselect all rows */
			$('#membersGridView').yiiGridView('setSelectionColumn',{
					 'name':'selection[]','class':null,'multiple':true,'checkAll':'selection_all'
			});
			
		});		
							  
							  
		/*
		in update mode, if no games are members played ($dataProvider->totalCount===0) hide gridview  
		*/
		
		if ($isNewRecord === 0 && $dataProvider->totalCount===0) {
			$('#members-form-gridview').hide(); // or do $('#members-form-gridview').parent().toggle();
		}
		
		/*if its a new model, hide gridview*/
		if ($isNewRecord)  {
			$('#members-form-gridview').hide(); // hide gridview
		}
		
		
		$('select').on('change', function(e){//, 'load',
			console.log('changed');
			var host = '$host';
			var formName = 'members-form';
			var county = '$county';
			var city = '$city';
			
			if(this.id === 'members-state'){ 	//&& isNewRecord != 1
				var dataObj = {'dropdown':this.id, 'formName':formName, 'id':this.value, 'host':host, 'nxtElemIndex':county};
				dynoDropdowns(this, '#members-county', dataObj);
			} else if (this.id === 'members-county'){
				var dataObj = {'dropdown':this.id, 'formName':formName, 'id':this.value, 'host':host, 'nxtElemIndex':city};
			  	dynoDropdowns(this, '#members-city', dataObj);
			}
			
		});
	
		
});

"
);

?>