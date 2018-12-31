<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
?>


<?php //Pjax::begin(['id'=>'branchesGrid']); ?>
<?php Pjax::begin([
	'id'=>'branchesGrid',
	'enablePushState' => false,
]); ?>    
    
	<?= GridView::widget([
		'export' => false,
		'pjax' => true,
		'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'rowOptions'  => function ($model, $key, $index, $widget) {
				if ($index % 2 === 0) {
					return [ 'class' => 'success'];
				} else {
					return [ 'class' => 'danger'];
				}
		},
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			/*
			[
				'class' =>"kartik\grid\EditableColumn",
				'attribute'	=>'branch_name',
				'header'	=>'Header',
			],
			*/
            [
				'attribute'	=>'branch_name',
			],
			
		   [
			   'label' =>"Company",
			   'attribute' => 'companies_company_id',
			   'value'		=>'companies.company_name',
		   ],			
            'address',
			[
				'label' =>"Inception Date",
				'attribute' => 'date_created',
				'format' => ['date', 'php:d-M-Y'],
				'filter'=>DatePicker::widget([
						'model' => $searchModel,
						'attribute' => 'date_created',	
						'options' => ['placeholder' => 'Inception Date'],
						//'value' => '01-Feb-1996',
						//'value' => date('d-M-Y', strtotime('+2 days')),
						'pluginOptions' => [
							'autoclose'=>true,
							'format' => 'd-M-yyyy'//dd-M-yyyy
						]
				]),
				
			],
			
		   [
			   'label' =>"Status",
			   'attribute' => 'status',
			   'filter'=> ['1' => 'Active', '0' => 'Dormant'],
			   'value'=>function($model){
				   return $model->status == 1 ? "Active" : "Dormant";
			   }
		   ],			
            // 'location',

            // buttons
            ['class' => 'yii\grid\ActionColumn',
            'header' => "Action",
            'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('', $url, ['title'=>'View Branch', 'class'=>'glyphicon glyphicon-eye-open']);
                    },
                    'update' => function ($url, $model, $key) {
						return 
						Html::a('', $url,['title'=>'Edit Branch','class'=>'glyphicon glyphicon-pencil','id'=>'modalUpdateButton']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('', $url, 
                        ['title'=>'Delete Branch', 
                            'class'=>'glyphicon glyphicon-trash',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this Branch(s)?'),
                                'method' => 'post']
                        ]);
                    }
                ]

            ], // ActionColumn


            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?>