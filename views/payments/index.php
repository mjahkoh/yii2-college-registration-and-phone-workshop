<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Payments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payments-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //Html::a(Yii::t('app', 'Create Payments'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'id'=> 'payments-index-gridview',
		'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
				'attribute'	=>'jobid',
				'label' => 'Job #',
				'format' => 'raw',
				'value' => function ($model, $key, $index) {
                	return Html::a($model->job_id, ['payments/payments-per-job', "id"=>$model->job_id]);				
				},
			],
            [
				'attribute'	=>'job_id',
				'label' => 'Client',
				'format' => 'raw',
				'value' => function ($model, $key, $index) {
                	return Html::a($model->jobs->jobsMembers->name, ['payments/create'], ['class'=>"popupModal","data-key"=>$model->id,"data-charges"=>$model->amount]);				
				},
			],
            [
				'attribute'	=>'amount',
				'label' => 'Amount paid',
				'value' => function ($model, $key, $index) {
					return number_format($model->amount, 0);
				}
			],
            [
				'attribute'	=>'date_of_payment',
				'label' => 'Date of payment',
				'format' => ['date', 'php:d-M-Y']
				//'contentOptions'=>['style'=>'width: 10%;text-align:center;vertical-align: middle;'],
				//'headerOptions'=>['class'=>'active', 'style'=>'text-align:center;vertical-align: middle;'],				
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
