<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\PhoneModels;
use app\models\Jobs;
use yii\bootstrap\Modal;
use yii\helpers\Url ;
use yii\helpers\ArrayHelper;

use app\models\JobsMembers;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JobsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jobs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jobs-index">
                <strong>Dynamic Dropdown, Saving multiple models (Complex  forms), Hidden inputs, Kartik Date Picker, Option boxes</strong>
 
				<p>Saves multiple models using Transactions (Jobs & MembersJobs) and sends a jobs card confirmation sms to client.<br />
				By default, the loginForm uses the Jobs Model for logging purposes. To change that to Members Model amend the web configuration file, identityClass in the Components section and also LoginForm Model. Replace all occurences of the JobsMembers model to Members
</p>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin([
		'id'=>'pjax-container',
		'enablePushState' => false,
	]); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Jobs', ['create'], ['class' => 'btn btn-success']) ?>
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
	
<?php
    Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'modal',
    'size' => 'modal-lg',
	//'header' => "<center><h4 class='modal-title'>Make Payments - Job ID: $job</h4></center>", //working 
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
    ]);
?>
    <div id='modalContent'></div>
<?php Modal::end();?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'jobs-gridview',
		'rowOptions'  => function ($model) {
					return [ 'value' => $model->id, 'class'=> 'checkbox-row', 'id'=> 'checkbox'];
		},
        'columns' => [
            [
				'attribute'	=>'client_id',
				'format' => 'raw',
				'filter' => Html::activeDropDownList($searchModel, 'client_id', 
					ArrayHelper::map(JobsMembers::find()->asArray()->all(), 'id', 'name'),['class'=>'form-control','prompt' => 'Select Client','multiple' => false,]),				
				'value' => function ($model, $key, $index) { 
                	return Html::a($model->jobsMembers->name, ['payments/create'], ['class'=>"popupModal", "data-key" => $model->id, "data-clientid" => $model->client_id, "data-charges" => $model->charges]);				
				},
			],
			
			
            [
				'attribute'	=>'telephone',
				'format' => 'raw',
				'value' => function ($model, $key, $index) {
					return Html::a($model->jobsMembers->tel_prefix . $model->jobsMembers->tel, ['jobs/view', 'id' => $model->id]);
				}
			],
			
            [
				'attribute'	=>'phone_make_id',
				'value' => function ($model) {
					return ($model->phoneMakes->make);
				}
			],
            [
				'attribute'	=>'phone_model_id',
				'value' => function ($model) {
					return ($model->phoneModels->model);
				}
			],
			
            [
				'attribute'	=> 'problem',
				'label'		=>'Problem',
			],
			
            [
				'attribute'	=>'charges',
				'value' => function ($model) {
					return (number_format($model->charges, 2));
				}
			],
            [
				'attribute'	=> 'charges',
				'label'		=>'Total Payments',
				'value'=>function($model){
					//$totalpayments  = 0;
					$sql = "select sum(amount) as totalpayments from payments where job_id=".$model->id;
					$check = Yii::$app->db->createCommand($sql)->queryOne(); //only once per year
					if ($check['totalpayments']) {	
						$model->totalpayments = $check['totalpayments'];
					}
					return number_format($model->totalpayments,2);
	            }
			],
            [
				'attribute'	=> 'balance',
				'label'		=>'Balance',
				'value'=>function($model){
					//$totalpayments  = 0;
					return number_format($model->charges - $model->totalpayments ,2);
	            }
			],
			
				
            [
				'attribute'	=>'sendsmsmessage',
				'label' => 'Send Sms',
				'format' => 'raw',
				'value' => function ($model, $key, $index) {
                	return Html::a('Send Sms', ['jobs/index', 'member' => $model->client_id], ['class'=>"popupSmsModal", "data-key"=> $model->client_id, "data-name" => $model->phone_make_id] );	//	
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
				
			
            // 'date_job_commenced',
            // 'date_job_completed',
            // 'members_allocated_id',
            // 'status',

		   [
				'class' => 'yii\grid\ActionColumn' ,
				'header' => "",
				'template' => '{view} {update} {delete}',
				'buttons' => [
					'view' => function ($url, $model) {
						$url = [
							'/jobs/view', 'id' => $model['id']
						];
							return Html::a('', $url, ['title'=>'View Phone Model', 'class'=>'glyphicon glyphicon-eye-open']);
					},
					'update' => function ($url, $model) {
						$url = [
							'/jobs/update', 'id' => $model['id']
						];
							return Html::a('', $url, ['title'=>'Update Job', 'class'=>'glyphicon glyphicon-pencil']);
					},
					'delete' => function ($url, $model) {
							$url = [
								'/jobs/delete' , 'id' => $model['id']
							];
							return Html ::a( '' , $url, [
								'title' => 'Delete Job' ,	
								'class'=>'glyphicon glyphicon-trash',
								'data-confirm' => Yii ::t( 'yii' ,'Are you sure you want to delete this item?' ),
								'data-method' => 'post' ,
							]);
					},
				],
			   
		   ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<?php

$this->registerJs(
"
$(document). ready (function (){

	//hide the send sms messages button untill at least one row is checked
	$('#modalMultipleButton').hide();
		

	$('.popupModal').click(function(event) {
		event.preventDefault();
		event.stopImmediatePropagation();
		//load ajax and show model 
		
		//start
		
		//working
		id = $(this).attr('data-key');
		charges = $(this).attr('data-charges');
		dataObj = {
			'job':id,
		};
		console.log('job:'+id);
		console.log('charges:'+charges);
		$.ajax({
			url			: '" . Url::toRoute(["payments/create"]). "',
			type		:'get',
			data		:dataObj,
			dataType	:'html', 
			
			success:function(result){
				$('#modal').modal({show:true})
				.find('#modalContent')
				.html( result );
				//title = '<center><h4 class=\'modal-title\'>Make Payments - Job ID: ' + id + '</h4></center>';
				
				title = 'Make Payments - Job ID: ' + id + ' - charges: ' + formatNumbers(charges,2);
				//dynamically set the header for the modal
        		//dynamically set the header for the modal
        		document.getElementById('modalHeader').innerHTML = \"<button type='button' class='close'\" +
                                                                \"data-dismiss='modal' aria-label='Close'>\" +
                                                                \"<span aria-hidden='true'>&times;</span>\" +
                                                           \"</button>\" +
                                                           '<h4>' + title + '</h4>';														   

				//$('#modalContent').on('modal', function (event) {
				   //$(this).find('h3#modal-title').text(title);
				//});
				
				//$(this).find('h3#modal-title').text('title');
				//console.log(title);
				//$('#modal').find('.modal-title').text(title);  //working
				
				$('#modalContent').on('show.bs.modal', function (event) {
				   //title = 'Make Payments - Job ID: ' + id  ;
				   $(this).find('h3#modal-title').text('title');
				});				
				//console.log($('#modal').attr('title').value);
				//console.log($('#modal').modal.attr('title') ); 
				
				/////$('#modal').find('#modalContent').html( result ).modal('show');
				
				/////$('#modal').modal({show:true})	//show:true
				/////.find('#modalContent')
				/////.load($(this).html(result));
				
				//$('#modalContent').load($(this).attr(data));
				//$('#modal').find('#modalContent').html( data ).modal('show');
				//console.log(report);
				//$('.modal-body').html(data);
				//$('#modalContent').modal();
				//$('#modal').find('#modalContent').html( data ).modal('show');
				//$('#modal').find('#modalContent').html( data ).modal('show');
				//console.log(data);							
			},
		})
		
		.fail (function(xhr, status, errorThrown) {
			//alert('sorry problem heres');
			//console.dir(xhr);
			//console.log('Error: ' + errorThrown);
			console.log('Status' + status);
		});
		return false;
		//end
		
	});	
	

	// generic positive number decimal formatting function
	/*
	function formatNumbers(expr, decplaces)
	{
		// evaluate the incoming expression
		var val = eval(expr);
		// raise the value by power of 10 times the number of decimal places;
		// round to an integer; convert to string
		var str = '' + Math.round(val * Math.pow(10, decplaces));
		// pad small value strings with zeros to the left of rounded number
		while (str.length <= decplaces)
		{
		str = '0' + str;
		}
		// establish location of decimal point
		var decpoint = str.length - decplaces;	
		
		// assemble final result from:
		// (a) the string up to the position of the decimal point;
		// (b) the decimal point; and
		// (c) the balance of the string. Return finished product.
		return str.substring(0,decpoint) + '.' + str.substring(decpoint, str.length);
	}
	*/

	//this section to the end takes care of sending sms
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
				url			: '" . Url::toRoute(["jobs/create-sms"]). "',
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
			
			//in our context, the keys to be submitted to the controller are the Primary ID
			//in the form '{'id':2,'problem':'charging'}'
			//we need to retrieve the id part only
			var keys = $('#jobs-gridview').yiiGridView('getSelectedRows'); 
			
			
			//var other = $('#w0').yiiGridView('getSelectedRows'); 
			//console.dir(other);
			//for (String s : keys) 
			//{
			//	console.log(s.id);
			//};
			console.dir(keys);
			var selection = [];
			$.each(keys, function(index, chunk) {
				console.log('chunk:'+chunk.id);
				selection.push(chunk.id);
			});

			//start
			console.log('modalMultipleButton:');
			//var data = JSON.stringify(keys);
			var dataObj = {
				'member':selection,
				'multiple':true
			};
			console.dir(keys);
			$.ajax({
				url			: '" . Url::toRoute(["jobs/create-sms"]). "',
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
		$('#jobs-gridview .select-on-check-all').on('change', function(e){//, 'load',
			var keys = $('#jobs-gridview').yiiGridView('getSelectedRows'); 
			console.log('length ' + keys.length);
			hideShow(keys);
		});	
		
		//this tracks the Gridviews id clicked of the checkbox
		$('#jobs-gridview tr').on('click', function(e){//, 'load',
			var id = $(this).data('key'); //div class ID
			console.log('id of row clicked:' + id);
			
			var keys = $('#jobs-gridview').yiiGridView('getSelectedRows'); 
			console.log('length tr::' + keys.length);
			hideShow(keys);
			
		});	
		
		/*
		as soon as the members selected is greater than Zero, Unhide
		the send message button
		*/
		
		$('#jobs-gridview').on('click', function(e){//, 'load',
			//in gridview, meterreading is the id of the Gridview. keys gives an array of selected rows
			var keys = $('#jobs-gridview').yiiGridView('getSelectedRows'); 
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
