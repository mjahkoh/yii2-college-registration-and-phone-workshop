<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url ;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PhoneMakesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Phone Makes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phone-makes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Phone Makes'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'id'=> 'phone-makes-index-gridview',
		'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
				'attribute'	=>'make',
				'format' => 'raw',
				'value' => function ($model) {
					$url = Url::to([
					'/phone-models/index',
					'id' =>$model->id]);
					$options = [];
					return Html::a($model->make, $url, $options);
				}
			],
            //'make',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
