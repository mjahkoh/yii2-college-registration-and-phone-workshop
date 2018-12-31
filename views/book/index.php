<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Authors;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Books');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">
<b>Grid Filtering by Static drop down</b>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Book'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin([
	'id'=>'booksGrid',
	'enablePushState' => false,
]); ?> 


<?php 
//$form = ActiveForm::begin();

echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
			[
				'label' =>"Author",
				'attribute' => 'author_id',	
				'value' => function ($model) {
					return $model->authors->name;
				}
			],
			'book_code',
            'synopsis:ntext',
            'color',
			[
				'label' =>"Publish Date",
				'attribute' => 'publish_date',	
				'format' => ['date', 'php:d-M-Y']
			],
            // 'sell_amount',
            // 'buy_amount',
			[
				'label' =>"Status",
				'attribute' => 'status',
				'format' => 'raw',
			   'filter'=> ['1' => 'Active', '0' => 'Dormant'],
			   'value'=>function($model){
				   return $model->status == 1 ? "Active" : "Dormant";
			   }
			],
            // 'author_id',
            // 'name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); 

// Add other fields if needed or render your submit button
echo '<div class="text-right">' . 
     Html::submitButton('Submit', ['class'=>'btn btn-primary']) .
     '<div>';
?>
<?php
//ActiveForm::end();
?>

<?php Pjax::end(); ?></div>
