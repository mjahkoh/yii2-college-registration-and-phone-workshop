<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Units;
use app\models\Authors;
use yii\helpers\ArrayHelper;
use app\models\Streams;
use yii\widgets\Pjax;

use kartik\grid\GridView;
use kartik\grid\EditableColumn;
use kartik\editable\Editable;
use kartik\widgets\ColorInput;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;
use kartik\widgets\DepDrop;
use kartik\date\DatePicker;
use kartik\builder\TabularForm;
/* @var $this yii\web\View */
/* @var $model app\models\MarksMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="marks-master-form">

    <?php $form = ActiveForm::begin(['id' => 'marksMaster',]); ?>

    <?php //$form->field($model, 'members_id')->textInput() ?>

    <?php $form->field($model, 'date_of_exam')->textInput() ?>
		<?php 	
			$model->date_of_exam = date('d-M-Y', strtotime($model->date_of_exam));//d-M-Y
			echo $form->field($model, 'date_of_exam')->widget
			(DatePicker::classname(), [
				'options' => ['placeholder' => 'Date of Exam'],
				'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'dd-M-yyyy'
				]
			]);
		?>


<?= $form->field($model, 'unit')->dropDownList(ArrayHelper::map(Units::find()->orderBy('unit ASC')->all(), 'id', 'unit'), ['prompt'=>'-Choose your Unit-']);?>

    <?= $form->field($model, 'total_marks')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

	<?php ActiveForm::end(); ?>
	
<?php	
Pjax::begin(['id'=>'indexmarksMaster']); 
echo  Html::beginForm([''], 'post', ['data-pjax' => '1', 'id' => 'indexExampleFromarray']);
$attribs = $model->formAttribs;


echo TabularForm::widget([
    // your data provider
    'dataProvider'=>$dataProviderArray,
	//'id' => 'kv-grid-tableID',

    // formName is mandatory for non active forms
    // you can get all attributes in your controller 
    // using $_POST['kvTabForm']
    'formName'=>'kvTabForm',
    
    // set defaults for rendering your attributes
    
	'attributeDefaults'=>[
        'type'=>TabularForm::INPUT_TEXT,
    ],
    
    // configure attributes to display
    'attributes'=> $attribs,
		
    
    // configure other gridview settings
    'gridSettings'=>[
        'panel'=>[
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Manage Marks</h3>',
            'type'=>GridView::TYPE_PRIMARY,
            'before'=>false,
            'footer'=>false,
            'after'=>Html::button('<i class="glyphicon glyphicon-plus"></i> Add New', ['id'=>'addNew', 'type'=>'button', 'class'=>'btn btn-success kv-batch-create']) . ' ' . 
                    Html::button('<i class="glyphicon glyphicon-remove"></i> Delete', ['type'=>'button', 'class'=>'btn btn-danger kv-batch-delete']) . ' ' .
                    Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Save', ['type'=>'button', 'class'=>'btn btn-primary kv-batch-save'])
        ]
    ]
]);
echo Html::endForm();
Pjax::end(); 
?>
</div>
