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
		'options' => ['data-pjax' => true],
		'enableAjaxValidation' => false,
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

	console.log('payments-form');

	<!-- //start before submit -->
	$('#branches-form').on('beforeSubmit', function(event, jqXHR, settings) {
			event.preventDefault();
			event.stopImmediatePropagation();
			///alert('works');

			var form = $(this);
			if(form.find('.has-error').length) {
					alert('fsdfsd');
					return false;
			}

			$.ajax({
					url			: '" . Url::toRoute(["branches/create-modal-ajax"]). "',
					type		: 'POST',
					data		: form.serialize(),
					dataType	:'json', 
					success: function(response) {
							console.log(response);
							$('#modal').modal({show:true});
							form.trigger('reset');
							
							//the following reloads the pjax container ie Gridview but closes the Modal
							//$.pjax.reload({container:'#pjax-container'});
					}
			})
			
			//.done (function() {alert('success');})
			.fail (function(xhr, status, errorThrown) {
				alert('sorry problem heres');
				console.log('Error: ' + errorThrown);
				console.log('Status' + status);
				console.dir(xhr);
			});
			
			
			return false;
	});
	//end submit

});
"
);
?>
