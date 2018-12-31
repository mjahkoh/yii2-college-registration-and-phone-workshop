<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Images */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="images-form">

    <?php $form = ActiveForm::begin(['id'=> 'companies-form'. time(),'options' => ['enctype' => 'multipart/form-data']]); ?>


    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
	<?php 
		// in update, you can only upload a single image
		$multiple =  (isset($update)) ?  false : true;
	?>
	<?= $form->field($model, 'imageFiles[]')->widget(FileInput::classname(), [
			'options' => ['multiple' => $multiple, 'accept' => 'image/*'],
		]);	
	?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
