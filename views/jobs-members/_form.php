<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\JobsMembers;
use app\models\Countrys;
use app\models\Settings;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Members */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="members-form">

    <?php $form = ActiveForm::begin(['id'=> 'members-'. time()]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?php $telprefix = ArrayHelper:: map (Countrys::find()->orderBy('country ASC')-> all() , 'international_tel_code' , 'international_tel_code' ) ;?>
		<div style="width: 100%; overflow: hidden;">
			<div style="width: 300px; float: left;">
			<div style="width: 300px; display: table-cell;"> 
	<?php 
	$defaultvalue = Settings::getDefaultCountryPrefix();
	echo $form->field($model,'international_tel_code')->dropDownList(
					$telprefix ,
					[
						'value' => $defaultvalue
					]) ; 
	?>
				<?php //$form->field($model, 'tel_prefix')->dropDownList($model->telprefix) ?>
			</div>
			<div class="help-block"></div></div>
			<div> <div style="display: table-cell;"> 
				<?= $form->field($model, 'tel')->textInput(['maxlength' => true]); ?>	
			</div>
			<div class="help-block"></div></div>
		</div>

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

	<?php
		if ($id == JobsMembers::STAFF_MEMBER) {
			echo $form->field($model, 'username')->textInput(['maxlength' => true]);
			echo $form->field($model, 'password')->textInput(['maxlength' => true])->passwordInput(); 
			echo $form->field($model,'repeatpassword')->textInput(['maxlength' => true])->passwordInput(); 
			echo $form->field($model, 'email')->textInput(['maxlength' => true]); 
			$authItems = ArrayHelper::map($authItems, 'name', 'name');
			/////echo $form->field($model, 'permissions')->checkboxList($authItems); 	
			//echo Html::activeHiddenInput($model, 'category', ['value'=> Members::STAFF_MEMBER]); 
		} else {
			//echo Html::activeHiddenInput($model, 'category', ['value'=> JobsMembers::CLIENTELLE]); 
		}
		/**/
	?>
	<?php 
		echo Html::activeHiddenInput($model, 'category', ['value'=> $id]); 
	?>
	<?php
		/*
		if ($id == JobsMembers::STAFF_MEMBER) {
			echo $form->field($model, 'status')->radioList($model->staffstatus); 
		}
		*/
		?>	

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
