<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url ;
/* @var $this yii\web\View */
/* @var $searchModel app\models\StatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'States');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="states-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create States'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'rowOptions'  => function ($model) {
				if ($model->state_id % 2 === 0) {
					return [ 'class' => 'success'];
				} else {
					return [ 'class' => 'danger'];
				}
		},
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'state_id',
			[
				'attribute'	=>'state_id',
				//'value'		=>'countys.county',
				'format' => 'raw',
				'label'		=>'State',
				'value' => function ($model) {
					$url = Url::to([
					'/states/view',
					'id' =>$model->state_id]);
					$options = [];
					return Html::a($model->state_name, $url, $options);
				}
			],
			
			
            //'state_name',
            'state_code',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
