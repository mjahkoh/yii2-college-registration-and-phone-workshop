<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MarksMasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="marks-master-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'members_id') ?>

    <?= $form->field($model, 'date_of_exam') ?>

    <?= $form->field($model, 'unit') ?>

    <?= $form->field($model, 'total_marks') ?>

    <?php // echo $form->field($model, 'class') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
