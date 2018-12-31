<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Setting Cookies';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="set-cookies">
    <h1><?= Html::encode($this->title) ?></h1>
   
<p>It is polite to welcome people in their own language:</p>
<ul>
    <li class="zhs" lang="zh-Hans">??</li>
    <li class="zht" lang="zh-Hant">??</li>
    <li class="el" lang="el">?a??s???sate</li>
    <li class="ar" lang="ar">???? ?????</li>
    <li class="ru" lang="ru">????? ??????????</li>
    <li class="din" lang="din">Kudual</li>
</ul>   
   
    <p>Enter the cookie name :</p>
   
    <?php $form = ActiveForm::begin([
        'id'=>'setcookies-form',
        'options'=>['class'=>'form-horizontal'],
        'fieldConfig'=>[
            'template'=>"{label}\n<div class=\"col-lg-3\">
                        {input}</div>\n<div class=\"col-lg-5\">
                        {error}</div>",
            'labelOptions'=>['class'=>'col-lg-2 control-label'],
        ],
    ]); ?>
       
       
        <?= $form->field($model,'cookie_name')->textInput(['maxlength' => true]); ?>
		<?= $form->field($model,'value')->textInput(['maxlength' => true]); ?>
       
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-11">
                <?= Html::submitButton('Set cookie',['class'=>'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div> 