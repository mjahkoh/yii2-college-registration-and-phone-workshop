<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MarksMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Marks Masters');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marks-master-index"> 
<b>Complex Forms - Master - Detail Multiple forms, Kartik Tabular Form, retrieves information from DB using Ajax. <br />
NB. It employs the Members model for storing students and lecturers info</b>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Marks Master'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<?php 

/**/
Pjax::begin(); 
    
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'fullName',
			
			[
				'attribute' => 'members_id',
				'format' => 'raw',
				'value' => function ($model) {return Html::a($model->members->fullName, ['/marks-master/view','id' =>$model->id]);
				//'value' => function ($model) {return Html::a($model->id, ['/marks-details/view','id' =>$model->id]);
				}
			],
			
			[
				'label' =>"Date of last exam",
				'attribute' => 'date_of_exam',
				'format' => ['date', 'php:d-M-Y']
			],	
			[
				'attribute' => 'unitsName',
				'format' => 'raw',
				'value' => function ($model) {return Html::a($model->unitsName, ['/units/view','id' =>$model->unit]);
				}
			],
            'total_marks',
            // 'class',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); 
 Pjax::end(); 
 
?>

</div>
