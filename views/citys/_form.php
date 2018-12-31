<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url ;
use yii\widgets\Pjax;
use yii\grid\GridView;

use app\models\States;
use app\models\Countys;
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
			print_r(Yii::$app->request->post('Citys'));
			$request = Yii::$app->request->post();
			$selected_state = $request['Citys']['state'];
			$selected_county = $request['Citys']['county_id'];
			//echo "option A:: <BR>";
		}  elseif ($model->isNewRecord) {
			$selected_state = $state;
			$selected_county = $county;
			//echo "option D:: <BR>";
		} else {	//update
			$selected_state = $model->countys->state_id;
			$selected_county = $model->county_id;
			//echo "option C:: <BR>";
		}
		/*
		elseif (is_array( Yii::$app->request->get('Citys'))) {
			print_r(Yii::$app->request->get());
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

		<div id="citys-gridview">
		<?php
			if (isset($dataProviderCitys)) {
				echo GridView::widget([
					'showOnEmpty'=> false,
					'dataProvider' => $dataProviderCitys,
					'columns' => [
						//'id',
					   [
						   'label' =>"State",
						   'attribute' => 'id',
						   'value'=>function($model){
							   return $model->countys->states->state_name;
						   }
					   ],	
					   [
						   'label' =>"County",
						   'attribute' => 'id',
						   'value'=>function($model){
							   return $model->countys->county;
						   }
					   ],			
						[
							'attribute'	=>'city',
						],
			
						['class' => 'yii\grid\ActionColumn'],
					],
				]); 		
			}	
			?>
		</div>	

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
	 <?php Pjax::end();  ?>
	 
</div>

<?php
/**/
////print_r($dataProvider);
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
		$('#citys-state').val($selected_state);
		$('#citys-county_id').val($selected_county);
		
		//note the dynoDropdowns function is tucked in web/js/main.js
		
		var isNewRecord = $isNewRecord;
					
		
		$('select').on('change', function(e){//, 'load',
			
			var host = '$host';
			var formName = 'citys-form';
			var county = '$county';
			console.log('host:' + host);
			console.log('select:' + this.id );
			if(this.id === 'citys-state'){ 	//&& isNewRecord != 1
				var dataObj = {'dropdown':this.id, 'formName':formName, 'id':this.value, 'host':host, 'nxtElemIndex':county};
				dynoDropdowns(this, '#citys-county_id', dataObj);
			} 
			
			var selectedstate = $('#citys-state').val();
			var selectedcounty = $('#citys-county_id').val();
			console.log('selectedstate:' + selectedstate);
			console.log('selectedcounty:' + selectedcounty);
			if (selectedstate!= null && selectedcounty!= null){	//call pjax to populate the grid if both are fine
				console.log('bothe cool:' );
				
			  var dataObj = {
					'CitysSearch[state]':selectedstate, 
					'CitysSearch[county_id]':selectedcounty, 
			  };
			  e.preventDefault();
			  //CitysSearch[city]=&CitysSearch[city_ascii]=&CitysSearch[state]=2&CitysSearch[county_id]=1003
			  $.pjax({
				type: 'POST',
				url			: '" . Url::toRoute(["citys/create"]). "',
				container: '#citys-list-pjax',
				data: dataObj,
				dataType: 'application/json'
			  })
				
			}
			
		});
	
		
});

"
);

?>