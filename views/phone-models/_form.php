<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PhoneModels */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="phone-models-form">

    <?php $form = ActiveForm::begin(['id'=> 'phone-models-'. time()]); ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>

    <?=  Html::activeHiddenInput($model, 'phone_make_id', ['value'=> $modelphonemakes->id]); ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
