<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url ;
?>


<?
		 Pjax::begin();     
		 
		 echo GridView::widget([
        'dataProvider' => $dataProvider,
		'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
		'rowOptions'  => function ($model) {
				if ($model->city_id % 2 === 0) {
					return [ 'class' => 'success'];
				} else {
					return [ 'class' => 'danger'];
				}
		},
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'city_id',
            //'city',
			[
				'attribute'	=>'city',
				//'value'		=>'countys.county',
				'format' => 'raw',
				'label'		=>'City',
				'value' => function ($model) {
					$url = Url::to([
					'/citys/view',
					'id' =>$model->city_id]);
					$options = [];
					return Html::a($model->city, $url, $options);
				}
			],
            'city_ascii',
			
			
			[
				'attribute'	=>'city_id',
				'format' => 'raw',
				'label'		=>'State',
				'value' => function ($model) {
					$url = Url::to([
					'/countys/view',
					'id' =>$model->county_id]);
					$options = [];
					return Html::a($model->countys->states->state_name, $url, $options);
				},
			],

			[
				'attribute'	=>'county_id',
				'format' => 'raw',
				'label'		=>'County',
				'value' => function ($model) {
					$url = Url::to([
					'/countys/view',
					'id' =>$model->county_id]);
					$options = [];
					return Html::a($model->countys->county, $url, $options);
				}
			],
			/**/
			

			/**/

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	
<?php Pjax::end(); ?>