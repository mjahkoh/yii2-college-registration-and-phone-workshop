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
	
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>