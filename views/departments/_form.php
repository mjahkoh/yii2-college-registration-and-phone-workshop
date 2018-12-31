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
$companies = ArrayHelper:: map (Companies::find()->orderBy('company_name ASC')-> all() , 'id' , 'company_name' ) ;

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
