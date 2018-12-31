<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\States;
/* @var $this yii\web\View */
/* @var $model app\models\Countys */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="countys-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'county')->textInput(['maxlength' => true]) ?>

    <?php //$form->field($model, 'id')->textInput() ?>

    <?php //$form->field($model, 'state_id')->textInput() ?>
	<?php 
	//populate states
	$companies = ArrayHelper:: map (States::find()->orderBy('state_name ASC')-> all() , 'state_id' , 'state_name' ) ;
	
	echo $form->field($model,'state_id')->dropDownList(
						$companies ,
						[
							'prompt' => 'Choose State',
							
						]) ; 
	?>


<?php
/*
otherexaples

	//generating a dropdown from an array
	<?= $form->field($model, 'state_id')->radioList($model->paymenttype); ?>	

    <?= $form->field($model, 'category')->dropDownList([""=> "Select one"     , 1 => "General", 2 => "Others"], ['size' => 1]) ?>

$deduction = ArrayHelper:: map (charges::find()
		->where(['category' => Charges::OTHERS])
		->andWhere(['<>', 'paid_annualy' , Charges::PAID_ANNUALLY])
		->orderBy('charge ASC')-> all(),'id','charge' ) ;
echo $form->field($model,'deduction')->dropDownList(
					$deduction ,
					[
						'prompt' => 'Choose deduction',
						
					]) ; 
?>


*/
?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
