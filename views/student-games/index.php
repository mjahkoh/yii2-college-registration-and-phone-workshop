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

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Student Games'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
           // 'gamesid',
		   /*
		   [
			   'label' =>"Game",
			   'attribute' => 'gamesid',
			   'value'=>function($model){
				   return ($model->games->game); 
			   }
		   ],
		   */			
		   
		   [
			   'label' =>"Student",
			   'attribute' => 'studentid',
			   'format' => 'raw',
			   'value'=>function($model){
					$options = [];
					$url = Url::to([
					'/student-games/index-games-played',
					'id' =>$model->studentid]);
				   return Html::a($model->members->fullname, $url, $options);
			   }
		   ],			

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
