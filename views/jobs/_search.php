<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\JobsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jobs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'phone_make_id') ?>

    <?= $form->field($model, 'phone_model_id') ?>

    <?= $form->field($model, 'problem') ?>

    <?= $form->field($model, 'client_id') ?>

    <?= $form->field($model, 'charges') ?>

    <?php // echo $form->field($model, 'date_job_commenced') ?>

    <?php // echo $form->field($model, 'date_job_completed') ?>

    <?php // echo $form->field($model, 'staff_allocated_id') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
