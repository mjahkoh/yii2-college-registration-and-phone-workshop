<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\JobsMembers;
use yii\helpers\Url ;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MembersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Clientelle');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Clientelle'), ['create-clientelle' ], ['class' => 'btn btn-success']) ?>
    </p>
	
<p> 
<?= Html::button(Yii::t('app', 'Send Message'),
 [
	 'value'=>Url::toRoute('create-sms'),
	 'class' => 'btn btn-success', 
	 'style' => 'float:right', 
	 'id'=>'modalMultipleButton',
 ])
 ; ?>
 </p>	
	
<?php
    Modal::begin([
    'headerOptions' => ['id' => 'modalContent'],
    'id' => 'modal',
    'size' => 'modal-lg',
	'closeButton' => ['id' => 'close-button'],
	//'header' => "<center><h4 class='modal-title'>Make Payments - Job ID: $job</h4></center>", //working 
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
    ]);
?>
<div id='modalContent'></div>
<?php Modal::end();?>
	
	
<?php Pjax::begin(['id'=>'pjax-grid-view']);  ?>  

<?php

//echo "<input class='form-control' name='MembersSearch[memberscat2]' value='sdsds' type='hidden'>";

/*
//print_r( Yii::$app->request->post());
if (Yii::$app->request->isPjax) {
	echo "ispjax";
} else {
	echo "not ispjax";
}
*/
?>

<?php //$form = ActiveForm::begin(['options' => ['data-pjax' => true ]]); ?>

	<?= GridView::widget([
			//'id'=> 'members-index-gridview',
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'id' => 'members-gridview',
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],
	
				//'id',
            [
				'attribute'	=>'name',
				'format' => 'raw',
				'value' => function ($model, $key, $index) {
                	return Html::a($model->name, ['jobs/index-jobs-per-client', 'member' => $model->id] );		
				},
			],

				[
					'attribute'	=>'telephone',
					'value' => function ($model) {
						return ($model->tel_prefix . $model->tel);
					}
				],
				
				'national_id',
				[
					'attribute'	=>'status',
					'value' => function ($model) {
						return (
							($model->status === JobsMembers::STATUS_ACTIVE) ? 'Active' :
							(($model->status === JobsMembers::STATUS_INACTIVE) ? 'Disabled' : 'Deleted' )
						);
					}
				],
				[
					'attribute'	=>'category',
					'value' => function ($model) {
						return ($model->category === JobsMembers::STAFF_MEMBER ? 'Staff' : 'Client' );
					}
				],
				
				[
					'attribute'	=>'memberscat2',
					'label'	=>'memberscat2',
					'options' => [ 'id' => 'members-gridview-memberscat' ],
					'value' => function ($model) {
						return ('gfhgfhgfh');
					},
					'visible' => true,
					'hAlign'=>'right',
					'vAlign'=>'middle',
					'width'=>'1%',
				],
				
            [
				'attribute'	=>'sendsmsmessage',
				'label' => 'Send Sms',
				'format' => 'raw',
				'value' => function ($model, $key, $index) {
                	return Html::a('Send Sms', ['members/index', 'member' => $model->id], ['class'=>"popupSmsModal","data-key"=>$model->id,"data-name"=>$model->name ]);		
				},
			],
				
			[
				'class' => 'yii\grid\CheckboxColumn',
					'header' => Html::checkBox('selection_all', false, [
						'class' => 'select-on-check-all',
						'label' => 'Check All',
						]),
				'checkboxOptions' => function ($model, $key, $index, $column){
					return [ 
						"value" => 	 $model->id,
						"checked" => 0
	               
					];
				}
			],
				
		   [
				'class' => 'yii\grid\ActionColumn' ,
				'header' => "",
				'template' => '{view} {update} {delete}',
				'buttons' => [
					'view' => function ($url, $model) {
						$url = [
							'/members/view-clientelle', 'id' => $model['id']
						];
							return Html::a('', $url, ['title'=>'View Phone Model', 'class'=>'glyphicon glyphicon-eye-open']);
					},
					'update' => function ($url, $model) {
						$url = [
							'/members/update-clientelle', 'id' => $model['id']
						];
							return Html::a('', $url, ['title'=>'Update Job', 'class'=>'glyphicon glyphicon-pencil']);
					},
					'delete' => function ($url, $model) {
							$url = [
								'/members/delete-clientelle' , 'id' => $model['id']
							];
							return Html ::a( '' , $url, [
								'title' => 'Delete Member' ,	
								'class'=>'glyphicon glyphicon-trash',
								'data-confirm' => Yii ::t( 'yii' ,'Are you sure you want to delete this item?' ),
								'data-method' => 'post' ,
							]);
					},
				],
			   
		   ],

			],
		]); 
	?>
	
	<?php //ActiveForm::end(); ?>			
	
<?php Pjax::end(); ?>

</div>

<?php
if (Yii::$app->request->post()) {
	$request = Yii::$app->request->post();
	$defaultvalue = $request['memberscat'];
} else {
	$defaultvalue = JobsMembers::BOTH;
}

if (Yii::$app->request->get()) {
	////print_r(Yii::$app->request->get());
}

$this->registerJs(
"

	$(document). ready (function (){
		
		//select either the default or the option selected (posted)
		$('#memberscatID').val($defaultvalue);
		
		//hide
		$('th:nth-child(7)').hide();
		$('td:nth-child(7)').hide();
		
		//hide the send sms messages button untill at least one row is checked
		$('#modalMultipleButton').hide();
		
		
		var memberscat2 = $('#members-gridview input[name=\"MembersSearch[memberscat2]\"]');
		
		$('#memberscatID').on('change', function(event) {
				//console.log($('#memberscatID').val());
				var i = $('#members-gridview input');
				//onchange set the memberscat2 to be retrived later
				//var i = $('#members-gridview input[name='MembersSearch[memberscat2]']');
				//var i = $('#members-gridview input[name=\"MembersSearch[memberscat2]\"]');
				
				memberscat2.val($('#memberscatID').val());
				console.log(memberscat2.val());
		});
		
		// function to get the drop down working (staff/all/members)
		$('#members-form').on('beforeSubmit', function(event, jqXHR, settings) {
			event.preventDefault();
			memberscat2.val($('#memberscatID').val());
			category = $('#memberscatID').val();
			console.log(category);
			dataObj = {
				'memberscat2': category,
			};
			
			var parentId  = $('#members-gridview input');
			parentId.find('input.MembersSearch[memberscat2]').val(category);
			console.log('befire submit category selected'+category);
			$.pjax.reload(event, '#pjax-grid-view', {
				'push': true,
				'replace': false,
				'data': dataObj,
				'timeout': 5000,
				'scrollTo': 0,
				'maxCacheLength': 0
			});
			return false;
		});

		$('.popupSmsModal').click(function(event) {
			event.preventDefault();
			event.stopImmediatePropagation();
			//load ajax and show model 
			console.log('popupSmsModal:');
			//start
			
			//working
			var id = [$(this).attr('data-key')];
			//name = $(this).attr('data-name');
			//var id = JSON.stringify(theid);
			dataObj = {
				'member':id,
			};
			console.log('job:'+id);
			console.log('name:'+name);
			$.ajax({
				url			: '" . Url::toRoute(["members/create-sms"]). "',
				type		:'post',
				data		:dataObj,
				//dataType	:'json', 
				
				success:function(result){
					$('#modal').modal({show:true})
					.find('#modalContent')
					.html( result );
					
				},
			})
			
			.fail (function(xhr, status, errorThrown) {
				console.log('Status' + status);
			});
			return false;
			//end
			
		});	
	

		$('#modalMultipleButton').click(function(event) {
			event.preventDefault();
			event.stopImmediatePropagation();
			//load ajax and show model 
			
			//start
			console.log('modalMultipleButton:');
			var keys = $('#members-gridview').yiiGridView('getSelectedRows'); 
			//var data = JSON.stringify(keys);
			var dataObj = {
				'member':keys,
			};
			console.dir(keys);
			$.ajax({
				url			: '" . Url::toRoute(["members/create-sms"]). "',
				type		:'post',
				data		:dataObj,
				//dataType	:'json', 
				
				success:function(result){
					$('#modal').modal({show:true})
					.find('#modalContent')
					.html( result );
					
				},
			})
			
			.fail (function(xhr, status, errorThrown) {
				console.log('Status' + status);
			});
			return false;
			//end
			
		});	
		
		//when the gridviews select-all checkbox is checked hide/show the send sms button
		$('#members-gridview .select-on-check-all').on('change', function(e){//, 'load',
			var keys = $('#members-gridview').yiiGridView('getSelectedRows'); 
			console.log('length ' + keys.length);
			hideShow(keys);
		});	
		
		//this tracks the Gridviews id clicked in the checkbox column and gives the corresponding Unique ID
		$('#members-gridview tr').on('click', function(e){//, 'load',
			var id = $(this).data('key'); //div class ID
			console.log('id of row clicked:' + id);
			
			var keys = $('#members-gridview').yiiGridView('getSelectedRows'); 
			console.log('length tr::' + keys.length);
			hideShow(keys);
			
		});	
		
		/*
		as soon as the members selected is greater than Zero, Unhide
		the send message button
		*/
		
		$('#members-gridview').on('click', function(e){//, 'load',
			//in gridview, meterreading is the id of the Gridview. keys gives an array of selected rows
			var keys = $('#members-gridview').yiiGridView('getSelectedRows'); 
			hideShow(keys);
		});	
		
		
		function hideShow(keys) {
			if (keys.length > 0) {
				$('#modalMultipleButton').show();
				console.log('show' );
			} else {
				$('#modalMultipleButton').hide();
				console.log('hide' );
			}
		}
		
	
	});
	

"
);

?>	