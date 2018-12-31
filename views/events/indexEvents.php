<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\Url ;

use app\models\Authors;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthorsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Events');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authors-index">
    <h1><?= Html::encode($this->title). " on ".  Yii::$app->formatter->asDate($date,'dd-MMM-yyyy');?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>

<?= Html::button(Yii::t('app', 'Create event'),
 [
	 'value'=>Url::toRoute('create-modal'),
	 'class' => 'btn btn-success', 
	 'id'=>'modalCreateButton',
 ])
 ; ?>
 </p>	
	
<?php 
/*
	Modal::begin([
		'header'		=>	'<h4>Event</h4>', 
		'id'			=>	'modalCreateNew',
		'size'			=>	'modal-lg',//sm
		//'clientOptions' => ['backdrop' => 'static', 'keyboard' => false] ,
		]);

	echo "<div id='modalContentEvent'></div>";
	Modal::end();
*/
 ?>	

<?php 
	Modal::begin([
		'header'		=>	'<h4>Events</h4>', 
		'id'			=>	'modal',
		'size'			=>	'modal-lg',//sm
		//'clientOptions' => ['backdrop' => 'static', 'keyboard' => false] ,
		]);
		echo "<div id='modalContent'></div>";
	Modal::end();
?>	
	
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'author_id',
            [
				'attribute'	=>'title',
				'format' => 'raw',
				'value' => function ($model, $key, $index) { 
                	return Html::a($model->title, ['update/', "id"=> $model->id], ['class'=>"popupModal", "data-id" => $model->id]);				
				},
			],
			
            'description',
			[
			   'label' =>"Date Created",
			   'attribute' => 'date_created',
			   'format' => ['date', 'php:d-M-Y']
			],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?>
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

<?php
$this->registerJs(
"
$(document). ready (function (){

	$('#modalCreateButton').click(function(event) {
		event.preventDefault();
		event.stopImmediatePropagation();
		//load ajax and show model 
		
		//start
		
		//working
		date = $date;
		dataObj = {
			'date':date,
		};
		console.log('date:'+id);
		$.ajax({
			url			: '" . Url::toRoute(["events/create"]). "',
			type		:'get',
			data		:dataObj,
			dataType	:'html', 
			
			success:function(result){
				$('#modal').modal({show:true})
				.find('#modalContent')
				.html( result );
				
				$('#modalContent').on('show.bs.modal', function (event) {
				   //////////$(this).find('h3#modal-title').text('title');
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


	$('.popupModal').click(function(event) {
		console.log('zxczxczxc');
		event.preventDefault();
		event.stopImmediatePropagation();
		//load ajax and show model 
		
		//start
		
		//working
		id = $(this).attr('data-id');
		dataObj = {
			'id':id,
		};
		console.log('date:'+id);
		$.ajax({
			url			: '" . Url::toRoute(["events/update"]). "',
			type		:'get',
			data		:dataObj,
			dataType	:'html', 
			
			success:function(result){
				$('#modal').modal({show:true})
				.find('#modalContent')
				.html( result );
				
				$('#modalContent').on('show.bs.modal', function (event) {
				   //////////$(this).find('h3#modal-title').text('title');
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
