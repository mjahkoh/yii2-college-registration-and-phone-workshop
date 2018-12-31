<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

use app\models\Jobs;
use app\assets\AppAsset;
AppAsset::register($this);
/* @var $this yii\web\View */
/* @var */
$this->registerJsFile(
	'@web/js/job.js',
	['depends' => 'yii\web\JqueryAsset']
) ;

$this->title = $model->id;
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jobs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php //$this->beginBody([<body onload="<?php echo Yii::app()->controller->onloadFunction; ?">]);?>
<?php $this->beginBody();?>
<div class="jobs-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
		<?= Html::button(Yii::t('app', 'Make Payments'),
		 [
			 'value'=>Url::toRoute(['payments/create', 'job'=> $model->id]),
			 'class' => 'btn btn-success', 
			 'id'=>'modalJobsButton',
		 ]) ; 
		 ?>
		 </p>	
		 
		<?php 
			Modal::begin([
				'headerOptions' => ['id' => 'modalJobsHeader'],
				'header'		=>	'<h4>Payments</h4>', 
				'id'			=>	'modal',
				'size'			=>	'modal-lg',//sm
				//'clientOptions' => ['backdrop' => 'static', 'keyboard' => false] ,
				]);
				echo "<div id='modalContent'></div>";
			Modal::end();
		?>	
		
		
    </p>
<?php // Jobs::STATUS_IN_COMPLETE == 10;?>
<?php //echo($model->status === Jobs::STATUS_IN_COMPLETE) ? 'In-Complete' : ($model->status === Jobs::STATUS_COMPLETE) ? 'Complete' : 'Un-repairable';?>

<h2><?= Html::encode('Client: ' . $model->jobsMembers->name . "  |   Tel:   " . $model->jobsMembers->tel_prefix . $model->jobsMembers->tel); ?></h2>
    <?= DetailView::widget([
        'id'=> 'jobs-view-detail-view',
		'model' => $model,
        'attributes' => [
            'id',
            /*
			[
				'attribute'	=>'client_id',
				'value' => function ($model) {
					return ($model->jobsMembers->name);
				}
			],
            [
				'attribute'	=>'members.telephone',
				'value' => function ($model) {
					return ($model->jobsMembers->tel_prefix . $model->jobsMembers->tel);
				}
			],
			*/
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
           // 'problem',
			
            [
				'attribute'	=> 'charges',
				'label'		=>'Charges',
				'value'=>function($model){
	                   return($model->countrys->currency. ' ' . number_format($model->charges,2));
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
				'label' =>"Date job Commenced",
				'attribute' => 'date_job_commenced',	
				'format' => ['date', 'php:d-M-Y']
			],
			[
				'label' =>"Date job Completed",
				'attribute' => 'date_job_completed',	
				'format' => ['date', 'php:d-M-Y']
			],
			[
				'label' =>"Staff Allocated",
				'attribute' => 'membersAllocated.name',	
			],
			[
				'label' =>"Status",
				'attribute' => 'status',	
				'value'=>function($model){
	                   return 
						 /*
						 $Myprovince = (
						 ($province == 6) ? "city-1" :
						  (($province == 7) ? "city-2" :
						   (($province == 8) ? "city-3" :
							(($province == 30) ? "city-4" : "out of borders")))
						 );*/
					   ($model->status == Jobs::STATUS_COMPLETE) ? 'Complete' :($model->status == Jobs::STATUS_IN_COMPLETE) ? 'In-Complete' : 'Un-repairable';
	            }
			],
        ],
    ]) ?>
<?PHP
/*
ternary
						 $Myprovince = (
						 ($province == 6) ? "city-1" :
						  (($province == 7) ? "city-2" :
						   (($province == 8) ? "city-3" :
							(($province == 30) ? "city-4" : "out of borders")))
						 );

*/
$id = $model->id;
$charges = $model->charges;

$this->registerJs(
"
$(document). ready (function (){


	$('#modalJobsButton').click(function(event) {
		event.preventDefault();
		event.stopImmediatePropagation();
		//load ajax and show model 
		
		//start
		
		//working
		id = $id;
		charges = $charges;
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
        		document.getElementById('modalJobsHeader').innerHTML = \"<button type='button' class='close'\" +
                                                                \"data-dismiss='modal' aria-label='Close'>\" +
                                                                \"<span aria-hidden='true'>&times;</span>\" +
                                                           \"</button>\" +
                                                           '<h4>' + title + '</h4>';														   

				
				$('#modalContent').on('show.bs.modal', function (event) {
				   $(this).find('h3#modal-title').text('title');
				});				
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
	
	

});
"
);
?>
