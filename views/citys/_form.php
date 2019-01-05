<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url ;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

use app\models\States;
use app\models\Countys;
use app\models\Citys;
//use app\models\Citys;
/* @var $this yii\web\View */
/* @var $model app\models\Citys */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="citys-form">

<?php Pjax::begin(['id' => 'citys-list-pjax']);  ?>

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city_ascii')->textInput(['maxlength' => true]) ?>

	<?php 
		//populate states
		
		if (is_array( Yii::$app->request->post('Citys')) ) {
			//print_r(Yii::$app->request->post('Citys'));
			$request = Yii::$app->request->post();
			$selected_state = $request['Citys']['state'];
			$selected_county = $request['Citys']['county_id'];
			//echo "option Posted:: <BR>";
		}  elseif ($model->isNewRecord) {
			$selected_state = 0;
			$selected_county = 0;
			//echo "option New Record:: <BR>";
		} else {	//update
			$selected_state =  $model->countys->state_id;
			$selected_county = $model->county_id;
			//echo "option Update:: <BR>";
		}
		/*
		elseif (is_array( Yii::$app->request->get('Citys'))) {
			//print_r(Yii::$app->request->get());
			$request = Yii::$app->request->get('CitysSearch');
			$selected_state = $request['state'];
			$selected_county = $request['county_id'];
			echo "option B:: <BR>";
		}
		*/
		//exit;
		//echo "selected_state:: $selected_state<br>";
		//echo "selected_county:: $selected_county<br>";
		$states = ArrayHelper:: map (States::find()->orderBy('state_name ASC')-> all() , 'state_id' , 'state_name' ) ;
		echo $form->field($model,'state')->dropDownList(
				$states ,
				[
					'prompt' => 'Choose your State',
					'value' => $selected_state
				]) ; 
	?>

	
	<?php
		/*
		//this works too if you want to populate the countries without pre-selecting the state
		$countys = ArrayHelper:: map (Countys::find()
		->joinWith(['states']) 
		->select([ 	"concat(county, ' - ', state_name) as county", "id"	])
		->orderBy('county ASC, state_name ASC')-> all() , 'id' , 'county' ) ;
	

	echo $form->field($model,'county_id')
				->widget(Select2::classname(), [
						'data'			=>	$countys,
						'language'		=>	'en',
						'options'		=>	['placeholder'=> 'select County'],
						'pluginOptions'	=> [
							'allowClear'=> true
						],
		]);		
	*/	
	
		//in update mode we have the state , populate the cities based on this condition
		
		$countys = ArrayHelper:: map (Countys::find()->where 
		(
			['state_id' => $selected_state])->orderBy('county ASC')->all() , 'id' , 'county' 
		) ;
		
		echo $form -> field ( $model , 'county_id' ) -> dropDownList($countys, ['prompt' => 'Choose your County']) ;
		
	?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
	 <?php Pjax::end();  ?>
	 
	<div id="citys-details" style="display: none;">
		<?php 
			echo $this->render('_index', [
				'model' => $citys,
				'dataProvider' => $dataProvider,
			]); 
		?>
	</div>	 
	 
</div>

<?php
/**/
////print_r($dataProvider);

//start Section - for populating the gridview with the citys depending with preselected county
$csrf_param = Yii::$app->request->csrfParam;  // #2
$csrf_token = Yii::$app->request->csrfToken;
//end  Section

if ($selected_county == null) {
	$selected_county = 0;
} 

$isNewRecord = $model->isNewRecord ? 1 : 0 ;
$host =  Url::toRoute(["/general/get-options"]);
$county = $model->county_id == null ? 0 : $model->county_id;
$this->registerJs(
"

$(document). ready (function (){
		
		//we use jquery to select the options in the county  dropdowns
		//$('#citys-state').val($selected_state);
		//$('#citys-county_id').val($selected_county);
		
		//note the dynoDropdowns function is tucked in web/js/main.js
		
		var isNewRecord = $isNewRecord;
		var csrf_param = '$csrf_param';
		var csrf_token = '$csrf_token';
					
		//on loading, if its update mode call ajax to populate the  grid
		if (isNewRecord == 0) {
			  var dataObj2 = {
					'id':$('#citys-county_id').val(), 
					'csrf_param':csrf_param, 
					'csrf_token':csrf_token, 
			  };
	
			  callAjaxToPopulateGrid(dataObj2);	
			  console.log('isNewRecord = 0');
		}

		$('select').on('change', function(e){//, 'load',
			
			var host = '$host';
			var formName = 'citys-form';
			var county = '$county';
			//console.log('host:' + host);
			//console.log('select:' + this.id );
			
			//if user clicks the state dropdwn also clear the grid since  the towns have changed
			if(this.id === 'citys-state'){ 	//
				var dataObj = {'dropdown':this.id, 'formName':formName, 'id':this.value, 'host':host, 'nxtElemIndex':county};
				dynoDropdowns(this, '#citys-county_id', dataObj);
				
				$('#citys-county_id').val('');	//reset the countys to [select County]
				
				//clear the gridview or delete the table rows or hide gridview div
				$('#citys-details').hide();
				
			} 
			
			var selectedstate = $('#citys-state').val();
			var selectedcounty = $('#citys-county_id').val();
			console.log('selectedstate' + selectedstate);
			console.log('selectedcounty--' + selectedcounty);
			
			//var oList = document.getElementById('citys-county_id');
			
			//var sChosenItemValue = oList.options[oList.selectedIndex].value;
			///console.log('sChosenItemValue--' + sChosenItemValue);
			//console.log('selectedcounty:::' + citys-county_id.options[citys-county_id.selectedIndex].value);
			
			if (selectedstate!= null && selectedcounty!= null){	//call ajax to populate the grid if both are fine
				console.log('selectedcounty:' + selectedcounty);
				  var dataObj = {
						'id':selectedcounty, 
						'csrf_param':csrf_param, 
						'csrf_token':csrf_token, 
				  };
				  e.preventDefault();	  	
				  callAjaxToPopulateGrid(dataObj);	
				  console.log('state2 = 0');
			}
			
		});
	
	
		function callAjaxToPopulateGrid(dataObject){
			  
			  $.ajax({
					type: 'GET',
					url			: '" . Url::toRoute(["citys/ajax-view"]). "',
					data: dataObject,
					dataType: 'html',
					'success' : function(data){ // #5
						$('#citys-details').html(data);
						$('#citys-details').show();
					}
			  })
			  .fail (function(xhr, status, errorThrown) {
				alert('sorry problem heres');
				console.log('Error: ' + errorThrown);
				console.log('Status' + status);
				console.dir(xhr);
			  });
				  
		}
		
});

"
);

?>