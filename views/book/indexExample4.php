<?php
use kartik\builder\TabularForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\grid\EditableColumn;
use kartik\editable\Editable;
use kartik\widgets\ColorInput;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;
use kartik\widgets\ActiveForm;
use kartik\widgets\DepDrop;
use yii\helpers\Url ;
use app\models\Authors;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Books');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode('TabularForm usage  without ActiveForm') ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Book'), ['create-index-example1'], ['class' => 'btn btn-success']) ?>
    </p>
<?php 

//Pjax::begin(); 

// Add other fields if needed or render your submit button
echo '<div class="text-right">' . 
     Html::submitButton('Submit', ['class'=>'btn btn-primary']) .
     '<div>';

//ActiveForm::end();
//Pjax::end(); 
?>
    <h1><?= Html::encode('TabularForm usage without ActiveForm') ?></h1>
	
<?php
Pjax::begin(['id'=>'indexExample4Pjax1']); 
echo  Html::beginForm([''], 'post', ['data-pjax' => '1', 'id' => 'indexExample4Form1']);
echo TabularForm::widget([
    // your data provider
    'dataProvider'=>$dataProvider,

    // formName is mandatory for non active forms
    // you can get all attributes in your controller 
    // using $_POST['kvTabForm']
    'formName'=>'kvTabForm',
    
    // set defaults for rendering your attributes
    'attributeDefaults'=>[
        'type'=>TabularForm::INPUT_TEXT,
    ],
    
	 // set entire form to static only (read only)
	/////'staticOnly'=>true,
	///'actionColumn'=>false,	
	
    // configure attributes to display
    'attributes'=>[
		'id'=>[ // primary key attribute
			'type'=>TabularForm::INPUT_HIDDEN, 
			'columnOptions'=>['hidden'=>true]
		], 
        'name'=>['label'=>'Book Name'],
		
		'author_id'=>[
				'label'=>'Author',
				'type'=>TabularForm::INPUT_DROPDOWN_LIST, 
				'items'=>ArrayHelper::map(Authors::find()->orderBy('name')->asArray()->all(), 'author_id', 'name'),
				'columnOptions'=>['width'=>'185px']
		],
		'buy_amount'=>['type'=>TabularForm::INPUT_TEXT],
		'sell_amount'=>['type'=>TabularForm::INPUT_TEXT],
		'status'=>[
			'type'=>TabularForm::INPUT_CHECKBOX , 
			'label'=>'Active?',
			'columnOptions'=>['vAlign'=>GridView::ALIGN_RIGHT, 'width'=>'90px']
		],			
        'publish_date'=>[
			'label'=>'Published On', 
			'type'=>TabularForm::INPUT_WIDGET,
			'widgetClass'=>\kartik\widgets\DatePicker::classname(), 
				'options'=> function($model, $key, $index, $widget) {
					return ($key % 2 === 0) ? [] :
					[ 
						'pluginOptions'=>[
							'format'=>'yyyy-mm-dd',
							'todayHighlight'=>true, 
							'autoclose'=>true
						]
					];
				},
				'columnOptions'=>['width'=>'170px']
		],
		
		
		/*
			'publish_date'=>[
				'type' => function($model, $key, $index, $widget) {
					//return ($key % 2 === 0) ? TabularForm::INPUT_HIDDEN : TabularForm::INPUT_WIDGET;
					return TabularForm::INPUT_WIDGET;
				},
				'widgetClass'=>\kartik\widgets\DatePicker::classname(), 
				'options'=> function($model, $key, $index, $widget) {
					return ($key % 2 === 0) ? [] :
					[ 
						'pluginOptions'=>[
							'format'=>'yyyy-mm-dd',
							'todayHighlight'=>true, 
							'autoclose'=>true
						]
					];
				},
				'columnOptions'=>['width'=>'170px']
			],
		*/
		
		
    ],
    
    // configure other gridview settings
    'gridSettings'=>[
        'panel'=>[
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Manage Books</h3>',
            'type'=>GridView::TYPE_PRIMARY,
            'before'=>false,
            'footer'=>false,
            'after'=>Html::button('<i class="glyphicon glyphicon-plus"></i> Add New', ['id'=>'addNew1', 'type'=>'button', 'class'=>'btn btn-success kv-batch-create']) . ' ' . 
                    Html::button('<i class="glyphicon glyphicon-remove"></i> Delete', ['type'=>'button', 'class'=>'btn btn-danger kv-batch-delete']) . ' ' .
                    Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Save', ['type'=>'button', 'class'=>'btn btn-primary kv-batch-save'])
        ]
    ]
]);
echo Html::endForm();
Pjax::end(); 
//Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Save', ['class'=>'btn btn-primary'])					
//Html::button('<i class="glyphicon glyphicon-floppy-disk"></i> Save', ['type'=>'button', 'class'=>'btn btn-primary kv-batch-save'])

///////////////////////////////////array stuff

?>
</div>

<?php

$this->registerJs(
"

$(document). ready (function (){

	jQuery('#addNew').click(function(event) {
			event.preventDefault();
			var form = $(this);
			if(form.find('.has-error').length) {
					alert('fs222dfsd');
					return false;
			}
			//alert(form);
			/*$(this).clone().insertAfter(this);
			*/
			//add new row start
			   var table = $('#indexExample4Formarray table tbody'); //gridViewId = your grid view 'id'
		
			   var rows = table.find('tr');
			   var rowNum = rows.size(); 
			   var columnsNum = $(rows[0]).find('td').size(); 
			   
			   for(var i = 0; i < rowNum; i++) {
				   var row = $(rows[i]);
		
				   //add a row after
				   $(row).after('<tr><td colspan='+ columnsNum +'>Lore Ipsum</td></tr>');
			   }	   
			
			// add new row end
	
	
			/*
			jQuery.ajax({
				url			: '" . Url::toRoute(["index.php/book/example-4-refresh-grid"]). "',
				type		:'POST',
				//data		: form.serialize(),
				//$('#indexExample4').kvTabForm;
				//data		: $('#indexExample4').kvTabForm,
				data: $('#indexExample4Form').serialize(),
				dataType	:'json', // <-------------expecting json from php
				
				success:function(data){
				   $.pjax.reload({container:'#indexExample4Pjax'});
				   alert(data);

					   
				   //$(nxtElem).append
				},
			})
			
			.fail (function(xhr, status, errorThrown) {
				//alert('sorry problem heres');
				console.dir(xhr);
				console.log('Error: ' + errorThrown);
				console.log('Status' + status);
			});
			*/
	
	
	
	});


});

"
);
?>
