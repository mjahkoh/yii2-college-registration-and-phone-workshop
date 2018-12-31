<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use kartik\select2\Select2;
use app\models\Carriers;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput
/* @var $this yii\web\View */
/* @var $model app\models\Companies */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="companies-form">

    <?php $form = ActiveForm::begin(['id'=> 'companies-form'. time(),'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'physical_location')->textInput() ?>

	<?= $form->field($model, 'imageFiles[]')->widget(FileInput::classname(), [
			'options' => ['multiple' => true, 'accept' => 'image/*'],
		]);	
	?>
		<div style="width: 100%; overflow: hidden;">
			<div style="width: 600px; float: left;">
			<div style="width: 600px; display: table-cell;"> 
				<?= $form->field($model, 'tel_prefix')->dropDownList($model->country_prefix) ?>
			</div>
			<div class="help-block"></div></div>
			<div style="margin-left: 620px;"> <div style="display: table-cell;"> 
				<?= $form->field($model, 'tel')->textInput(['maxlength' => true]); ?>	
			</div>
			<div class="help-block"></div></div>
		</div>
	
		<div style="width: 100%; overflow: hidden;">
			<div style="width: 600px; float: left;">
			<div style="width: 600px; display: table-cell;"> 
				<?= $form->field($model, 'mobile_prefix')->dropDownList($model->country_prefix) ?>
			</div>
			<div class="help-block"></div></div>
			<div style="margin-left: 620px;"> <div style="display: table-cell;"> 
				<?= $form->field($model, 'mobile')->textInput(['maxlength' => true]); ?>	
			</div>
			<div class="help-block"></div></div>
		</div>
	
	
		<div style="width: 100%; overflow: hidden;">
			<div style="width: 600px; float: left;">
			<div style="width: 600px; display: table-cell;"> 
				<?= $form->field($model, 'mobile2_prefix')->dropDownList($model->country_prefix) ?>
			</div>
			<div class="help-block"></div></div>
			<div style="margin-left: 620px;"> <div style="display: table-cell;"> 
				<?= $form->field($model, 'mobile2')->textInput(['maxlength' => true]); ?>	
			</div>
			<div class="help-block"></div></div>
		</div>
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
