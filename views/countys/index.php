<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url ;
use yii\helpers\ArrayHelper;

use app\models\States;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CountysSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Countys');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="countys-index">
<b>DropDown, Adding Classes to Rows in the GridView, generating dropdown from a Public Array declared in the countys model(paymenttype)</b>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Countys'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'rowOptions'  => function ($model, $key, $index, $column) {
				if ($index % 2 === 0) {
					return [ 'class' => 'success'];
				} else {
					return [ 'class' => 'danger'];
				}
		},
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'county',
			[
				'attribute'	=>'county',
				//'value'		=>'countys.county',
				'format' => 'raw',
				'label'		=>'County',
				'value' => function ($model) {
					$url = Url::to([
					'/countys/view',
					'id' =>$model->id]);
					$options = [];
					return Html::a($model->county, $url, $options);
				}
			],
			
            //'id',
            //'state_id',
			[
				'attribute'	=>'state_id',
				//'value'		=>'countys.county',
				'format' => 'raw',
				'label'		=>'State',
				'filter' => Html::activeDropDownList($searchModel, 'state_id', 
					ArrayHelper::map(States::find()->asArray()->all(), 'state_id', 'state_name'),['class'=>'form-control','prompt' => 'Select State','multiple' => false,]),				
				'value' => function ($model) {
					$url = Url::to([
					'/states/view',
					'id' =>$model->state_id]);
					$options = [];
					return Html::a($model->states->state_name, $url, $options);
				}
			],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
