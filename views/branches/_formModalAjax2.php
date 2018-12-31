<?php
/* @var $this yii\web\View */
/* @var $model app\models\Branches */
/* @var $form yii\widgets\ActiveForm */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
?>

<div class="branches-form">

<?php $form = ActiveForm::begin([
		'id'	  => "branches-form",
		'action' => ['branches/save'],
		'enableAjaxValidation' => true,
		'validationUrl' => 'validate',
]); ?>

<?= $this->render('input', [
		'model'		=> $model,
		'form'		=> $form,
]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>		

</div>
<?php
$this->registerJs(
"
$(document). ready (function (){

	$('#branches-form').on('beforeSubmit', function(event, jqXHR, settings) {
		var form = $(this);
		var formData = form.serialize();
		$.ajax({
			url: form.attr('action'),
			type: form.attr('method'),
			data: formData,
			success: function (data) {
				console.log('ok')
				$('#modal').modal({show:true});
				form.trigger('reset');
			},
			error: function () {
				console.log('baaadd')
			}
		});
	}).on('submit', function(event){
    event.preventDefault();


	});

});
"
);
?>
