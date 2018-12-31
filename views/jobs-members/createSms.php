<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\ResetPassword */

$this->title = 'Send Sms to '. $members['names'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jobs-members-create-sms">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please Enter Your Message:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'create-sms-form']); ?>
                <?= $form->field($model, 'smsmessage')->textArea(['rows' => 3]) ?>
				<?php echo Html::activeHiddenInput($model, 'memberssmsid', ['value'=> $members['membersID']]);  ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary']) ?>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
