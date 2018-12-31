<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Events */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="events-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //$form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

		<?php
			//working model of a date
			//$model->date_created = date('M-Y', strtotime('-1 days'));//d-M-Y
			//$model->date_created = date('d-M-Y',strtotime($model->date_created));//d-M-Y
			echo $form->field($model, 'date_created')->widget
			(DatePicker::classname(), [
				'options' => ['placeholder' => 'Date Created'],
				//'value' => '01-Feb-1996',
				//'value' => date('d-M-Y', strtotime('+2 days')),
				'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'd-M-yyyy'//dd-M-yyyy
				]
			]);/**/
		?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
