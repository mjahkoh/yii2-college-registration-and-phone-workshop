<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url ;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CompaniesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Companies');
$this->params['breadcrumbs'][] = $this->title;
?>

               <strong>Dropdowns, Multiple file Uploader</strong>  <br />

				<p>Static dropdowns derived from an array and multiple file uploader based on kartik FileInput Widget.</p>

<div class="companies-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Companies'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'id'=> 'companies-index-gridview',
		'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
				'attribute'	=>'company_name',
				'format' => 'raw',
				'value' => function ($model) {
					$url = Url::to([
					'/companies/update',
					'id' =>$model->id]);
					$options = [];
					return Html::a($model->company_name, $url, $options);
				}
			],
            [
				'attribute'	=>'tel',
				'format' => 'raw',
				'value' => function ($model) {
					return ($model->tel_prefix . $model->tel);
				}
			],
            'address', 
            //'status',
            //'date_created',
            // 'logo',
            // 'mobile',
            // 'mobile2',
            // 'slogan',
            // 'physical_location',
            // 'facebook_handle',
            // 'tweeter_handle',
            // 'email:email',

            //['class' => 'yii\grid\ActionColumn'],
		   [
				'class' => 'yii\grid\ActionColumn' ,
				'header' => "",
				'template' => '{view} {update} {delete}',
				'buttons' => [
					'view' => function ($url, $model) {
						$url = [
							'/companies/view', 'id' => $model['id']
						];
							return Html::a('', $url, ['title'=>'View Company', 'class'=>'glyphicon glyphicon-eye-open']);
					},
					'update' => function ($url, $model) {
						$url = [
							'/companies/update', 'id' => $model['id']
						];
							return Html::a('', $url, ['title'=>'Update Company', 'class'=>'glyphicon glyphicon-pencil']);
					},
					'delete' => function ($url, $model) {
						$url = [
							'/companies/delete', 'id' => $model['id']
						];
							return Html::a('', $url, ['title'=>'delete Company', 'class'=>'glyphicon glyphicon-trash']);
					},
				],
			   
		   ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
