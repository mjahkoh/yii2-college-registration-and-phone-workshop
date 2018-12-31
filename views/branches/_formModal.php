<?php
/* @var $this yii\web\View */
/* @var $model app\models\Branches */
/* @var $form yii\widgets\ActiveForm */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div class="branches-form">

<?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

		<?= $this->render('input', [
				'model'		=> $model,
				'form'		=> $form,
		]) ?>
		
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>		

</div>