<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Companies */

$this->title = $model->company_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="companies-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php 
		/*
			Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
		*/ 
		?>
    </p>

    <?= DetailView::widget([
        'id'=> 'companies-view-detail-view',
		'model' => $model,
        'attributes' => [
            //'id',
            'company_name',
            'address',
            'email:email',
            //'status',
            //'date_created',
			[
				'label' =>"Logo",
				'attribute' => 'Image',	
				'format'=> 'raw',
				'value' => function ($model) {   
					if ($model->logo!='') {
						$filenames = NULL;
						$sql = "select * from companies_files where company_id = " . $model->id;
						$files = Yii::$app->db->createCommand($sql)->queryAll();
						if (count($files)) {	
							foreach ($files as $index => $file) {
								$filenames.= '<img src ="' .Yii::$app->homeUrl  . $file['filename'] . '" height="100" width="auto"' .   '>  ';
							}
						}		
					  return ($filenames);
					} else {
						return 'no image';
					}   
				 },
					 				//'format'=> ['image', ['width' => 200, 'height' => 473 ]],
					
			],
			[
				'label' =>"Tel",
				'attribute' => 'tel',	
				//'value'=> Yii::$app->formatter->asDatetime($model->start_date),
				'value'=> 
					function($model){
						return ('+' . $model->tel_prefix .'-' .$model->tel ) ; 
					}
			],
			[
				'label' =>"Mobile",
				'attribute' => 'mobile',	
				//'value'=> Yii::$app->formatter->asDatetime($model->start_date),
				'value'=> 
					function($model){
						return ($model->mobile) ? '+' . $model->mobile_prefix .'-' .$model->mobile : NULL;
					}
			],
			[
				'label' =>"Mobile2",
				'attribute' => 'mobile2',	
				//'value'=> Yii::$app->formatter->asDatetime($model->start_date),
				'value'=> 
					function($model){
						return ($model->mobile2) ? '+' . $model->mobile2_prefix .'-' .$model->mobile2 : NULL;
					}
			],
			
            'slogan',
            'physical_location',
			'website_url',
            'facebook_handle',
            'tweeter_handle',
			
        ],
    ]) ?>

</div>
