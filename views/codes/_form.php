<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Countrys;
use yii\helpers\ArrayHelper;
use app\models\Settings;
/* @var $this yii\web\View */
/* @var $model app\models\Codes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="codes-form">

    <?php 
	$telprefix = ArrayHelper:: map (Countrys::find()->orderBy('country ASC')-> all() , 'international_tel_code' , 'international_tel_code' ) ;
	$defaultvalue = Settings::getDefaultCountryPrefix();
	$form = ActiveForm::begin(); 
		if ($model->isNewRecord) {
			echo $form->field($model, 'noofcharacters')->textInput(['value'=>'25']);
		} else {
			echo $form->field($model, 'code')->textInput(['maxlength' => true]);
		}
		?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
