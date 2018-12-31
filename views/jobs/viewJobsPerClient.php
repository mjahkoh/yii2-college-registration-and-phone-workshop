<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\bootstrap\Modal;
AppAsset::register($this);

use app\models\Jobs;

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
        <?= Html::a(Yii::t('app', 'Update'), ['update-jobs-per-client', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete-jobs-per-client', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
		<?= Html::button(Yii::t('app', 'Make Payments'),
		 [
			 'value'=>Url::toRoute(['create-modal', 'job'=> $model->id]),
			 'class' => 'btn btn-success', 
			 'id'=>'modalButton',
		 ]) ; 
		 ?>
    </p>
	
		<?php 
			Modal::begin([
				'header'		=>	'<h4>Payments</h4>', 
				'id'			=>	'modal',
				'size'			=>	'modal-lg',//sm
				//'clientOptions' => ['backdrop' => 'static', 'keyboard' => false] ,
				]);
				echo "<div id='modalContent'></div>";
			Modal::end();
		?>	
	
<?php // Jobs::STATUS_IN_COMPLETE == 10;?>
<?php //echo($model->status === Jobs::STATUS_IN_COMPLETE) ? 'In-Complete' : ($model->status === Jobs::STATUS_COMPLETE) ? 'Complete' : 'Un-repairable';?>

<h2><?= Html::encode('Client: ' . $model->jobsMembers->name . "   |  Tel:   " . $model->jobsMembers->tel_prefix . $model->jobsMembers->tel); ?></h2>

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
            'problem',
			
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
	                   return ($model->status == Jobs::STATUS_COMPLETE) ? 'Complete' :($model->status == Jobs::STATUS_IN_COMPLETE) ? 'In-Complete' : 'Un-repairable';
	            }
			],
        ],
    ]) ?>
