<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PhoneModelsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', $model->make);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Phone Makes'), 'url' => ['phone-makes/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phone-models-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Model', ['create','id'=> $model->id], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'id'=> 'phone-models-index-gridview',
		'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'model',
            //'phone_make_id',

           // ['class' => 'yii\grid\ActionColumn'],
		   /**/
		   [
				'class' => 'yii\grid\ActionColumn' ,
				'header' => "",
				'template' => '{view} {update} {delete}',
				'buttons' => [
					'view' => function ($url, $model) {
						$url = [
							'/phone-models/view', 'id' => $model['id']
						];
							return Html::a('', $url, ['title'=>'View Phone Model', 'class'=>'glyphicon glyphicon-eye-open']);
					},
					'update' => function ($url, $model) {
						$url = [
							'/phone-models/update', 'id' => $model['id'], 'make' => $model['phone_make_id']
						];
							return Html::a('', $url, ['title'=>'Update Phone Model', 'class'=>'glyphicon glyphicon-pencil']);
					},
					'delete' => function ($url, $model) {
							$url = [
								'/phone-models/delete' , 'id' => $model['id'], 'make' => $model['phone_make_id']
							];
							return Html ::a( '' , $url, [
								'title' => 'Delete Phone Model' ,	
								'class'=>'glyphicon glyphicon-trash',
								'data-confirm' => Yii ::t( 'yii' ,'Are you sure you want to delete this item?' ),
								'data-method' => 'post' ,
							]);
					},
				],
			   
		   ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
