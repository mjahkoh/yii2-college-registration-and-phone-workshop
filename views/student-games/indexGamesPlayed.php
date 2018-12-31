<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url ;


/* @var $this yii\web\View */
/* @var $searchModel app\models\StudentGamesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Student Games');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-games-index">
<?php //print_r($dataProvider);exit; ?>
    <h1><?= Html::encode($this->title) ." - Played by ". $model->fullname;?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Update Games'), ['student-games/update', 'id'=>$studentgamesmodel->id], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    


<?= GridView::widget([
	   'showOnEmpty'=> false,
	   'dataProvider' => $dataProvider,
	   'id' => 'membersGamesPlayedGridView',
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'games.game',
			
			/*
			[
				'class' => 'yii\grid\CheckboxColumn',
				'header' => Html::checkBox('index', false, [
						'class' => 'checkAll',
						'label' => 'Index',
						]),
				'checkboxOptions' => function ($model, $key, $index, $column)  {
					$checked = true ;
					return [ 
						"value" => 	 $index,	//Yii::$app->user->can($model['index']),
						"checked" => $checked,
					];
				}
			],
			*/

           // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>



<?php Pjax::end(); ?></div>
