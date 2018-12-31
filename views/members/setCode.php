<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Enter Serial Number';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-changepassword">
    <h1><?= Html::encode($this->title) ?></h1>
   
    <p>Please fill out the Serial Number :</p>
   
    <?php $form = ActiveForm::begin([
        'id'=>'code-form',
        'options'=>['class'=>'form-horizontal'],
        'fieldConfig'=>[
            'template'=>"{label}\n<div class=\"col-lg-3\">
                        {input}</div>\n<div class=\"col-lg-5\">
                        {error}</div>",
            'labelOptions'=>['class'=>'col-lg-2 control-label'],
        ],
    ]); ?>
       
        <?= $form->field($model,'code'); ?>
       
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-11">
                <?= Html::submitButton('Set Code',['class'=>'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div> 