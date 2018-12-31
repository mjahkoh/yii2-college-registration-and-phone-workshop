<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url ;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthorsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Rbac Controllers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authors-index">


    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<p> <?php echo Html::a('Click here to initialise Rbac', Url::to(['/rbac/init']), []); ?> </p>
    <p>
        <?= Html::a(Yii::t('app', 'Create Controller Permission'), ['create-controller-role'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
		   [
			   'label' =>"Name",
			   'attribute' => 'name',
			   'value'=>function($model){
				   return ucfirst($model->name); 
			   }
		   ],			
           // 'type',

            ['class' => 'yii\grid\ActionColumn',
				'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a('', ['remove', 'id' => $model->name], 
                        ['title'=>'Delete Controller Right', 
                            'class'=>'glyphicon glyphicon-trash',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this Controller Right?'),
                                'method' => 'post']
                        ]);
                    }
                ]
			],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>