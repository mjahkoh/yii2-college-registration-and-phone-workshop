<?php
use yii\helpers\Url ;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Companies;
use yii\helpers\ArrayHelper;
use app\models\Branches;
/* @var $this yii\web\View */
/* @var $model app\models\Departments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="departments-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //$form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?php 
//populate states
$companies = ArrayHelper:: map (Companies::find()->orderBy('company_name ASC')-> all() , 'company_id' , 'company_name' ) ;

echo $form->field($model,'company_id')->dropDownList(
					$companies ,
					[
						'prompt' => 'Choose your company',
						'onChange' => '
							$.post("' . Url::toRoute(["departments/branches-list?id="]) . '" + $(this).val(), function (data){
								$("select#departments-branch_id").html(data);
							});'
					]) ; 
?>
											
<?php
//populate citys
$branches = array();
$companyid = Yii::$app->request->post('company_id');
if ($companyid || !$model->isNewRecord) {
	$branches = ArrayHelper:: map (Branches::find()->where 
	(
		['branch_id' => $model->state])->orderBy('branch_name ASC')->all() , 'id' , 'branch_name' 
	) ;
} 
echo $form->field($model,'branch_id')->dropDownList(
										$branches,
										 [ 
										 'prompt' => 'Choose your Branch' ,
										 ]
);

?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$isNewRecord = $model->isNewRecord ? 1 : 0 ;
$branch_id = $model->branch_id == null ? 0 : $model->branch_id;
$this->registerJs(
"

	$(document). ready (function (){
	
			//trigger change
			$('#departments-company_id').trigger('change');
	
	
		$('select').on('change', function(e){//, 'load',
			
			var isNewRecord = $isNewRecord;
			var formName = 'departments-form';
			
			if(this.id === 'departments-company_id'){ 	
				var nxtElemIndex = $branch_id;
				var dropdown = 'company_id';
				var dataObj = {
					'dropdown':dropdown, 
					'id':this.value, 
					'nxtElemIndex':nxtElemIndex, 
					'formName':formName, 
					'isNewRecord':$isNewRecord
				};
				dynoDropdowns(this, '#departments-branch_id', dataObj);
			  
			} 
			
			
			
		});
		
	
		function dynoDropdowns(currElem, nxtElem, dataObj){
			$.ajax({
				url			: '" . Url::toRoute(["general/general-options"]). "',
				type		:'GET',
				data		:dataObj,
				dataType	:'json', // <-------------expecting json from php
				
				success:function(data){
				   $(nxtElem).empty(); // empty the field first here.
				   $(nxtElem).append($('<option></option>').attr('value', '').text('Please Select'));
				   //alert('data');
				   $.each(data, function(i, obj){
					   $('<option />', 
					   {
						   value:obj.value,
						   text:obj.text
					   }
						).appendTo(nxtElem);
				   });
				   
				   //now select the nxtElem selected index if its an update rec
				   if ($isNewRecord === false) {
					   $(nxtElem).val(dataObj.nxtElemIndex);
				   }
					   
				},
			})
			
			.fail (function(xhr, status, errorThrown) {
				//alert('sorry problem heres');
				console.dir(xhr);
				console.log('Error: ' + errorThrown);
				console.log('Status' + status);
			});
			
			
		};
		
		
		
	});

"
);

?>	