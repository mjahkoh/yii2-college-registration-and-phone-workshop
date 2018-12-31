<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url ;
use yii\helpers\ArrayHelper;

use app\models\Countys;
use app\models\States;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CitysSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Citys');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="citys-index">
<p>Auto Suggest DropDown Search (create view), Global Search,  Grid Filtering by Dynamic drop downs, Adding Classes to Rows in the GridView, retrieving Related data in a master-detail intermediary, table setup </p>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Citys'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
				'filter' => Html::activeDropDownList($searchModel, 'state', 
					ArrayHelper::map(States::find()->asArray()->all(), 'state_id', 'state_name'),['class'=>'form-control','prompt' => 'Select State','multiple' => false,]),				
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
				'filter' => Html::activeDropDownList($searchModel, 'county_id', 
				ArrayHelper::map(Countys::find()
				->where(['=','state_id', count(Yii::$app->request->get()) ? Yii::$app->request->post('CitysSearch')['state'] : 0])
				->asArray()
				->all(), 'id', 'county'),['class'=>'form-control','prompt' => 'Select County','multiple' => false,]),				
				'value' => function ($model) {
					$url = Url::to([
					'/countys/view',
					'id' =>$model->county_id]);
					$options = [];
					return Html::a($model->countys->county, $url, $options);
				}
			],
			

			/**/

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	
<?php Pjax::end(); ?></div>