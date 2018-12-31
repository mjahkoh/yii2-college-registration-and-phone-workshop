<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UnitsBookedByStudentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Units Booked By Students');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="units-booked-by-students-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'unit_id',
	        [
	               'label' =>"Student",
	               'attribute' => 'student_id',
	               'value'=>function($model){
				   		return $model->members->fullName;
				   }
	        ],

			/* this will work for unit, or the below code*/
	        [
	               'label' =>"Unit",
	               'attribute' => 'unitName',
	               'value'=>function($model){return $model->unitName;}
	        ],
			/*or this will work
	        [
	               'label' =>"Unit",
	               'attribute' => 'unit_id',
	               'value'=>function($model){return $model->units->unit;}
	        ],*/
	        [
	               'label' =>"semester",
	               'attribute' => 'semester',
	               'value'=>function($model){return  $model->semester ==1 ? "I" : $model->semester ==2 ? "II" : "III";}
	        ],			
            'academic_year',
			
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
