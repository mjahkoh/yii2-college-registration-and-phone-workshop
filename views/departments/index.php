<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DepartmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Departments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="departments-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<p>Dependant Dropdowns - Company to Branches using Ajax</p>
    <p>
        <?= Html::a(Yii::t('app', 'Create Departments'), ['departments/create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
			[
				'label' =>"Company",
				'attribute' => 'company_id',	
				'value' => function ($model) {
					return $model->companies->company_name;
				}
			],
			[
				'label' =>"Branch",
				'attribute' => 'branch_id',	
				'value' => function ($model) {
					return $model->branches->branch_name;
				}
			],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
