<?php
use yii\grid\GridView;

				echo GridView::widget([
					'showOnEmpty'=> false,
					'dataProviderCitys' => $dataProviderCitys,
					'columns' => [
						//'id',
					   [
						   'label' =>"State",
						   'attribute' => 'id',
						   'value'=>function($model){
							   return $model->countys->states->state_name;
						   }
					   ],	
					   [
						   'label' =>"County",
						   'attribute' => 'id',
						   'value'=>function($model){
							   return $model->countys->county;
						   }
					   ],			
						[
							'attribute'	=>'city',
						],
			
						['class' => 'yii\grid\ActionColumn'],
					],
				]); 