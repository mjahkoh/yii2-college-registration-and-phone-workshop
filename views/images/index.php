<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ImagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Images';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="images-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Images', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php 
	
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				'label' =>"File name",
				'attribute' => 'filename',	
				'format'=> 'raw',
			],
			[
				'label' =>"File location",
				'attribute' => 'filelocation',	
				'value'		=>'filelocation',
			],
			[
				'label' =>"Image",
				'attribute' => 'Image',	
				'format'=> 'raw',
				'value' => function ($model) {   
					if ($model->filename!='') {
					  return ('<img src =' .Yii::$app->homeUrl  . "uploads/" .  $model->filename . ' height="100" width="auto"' .   '>');
					} else {
						return 'no image';
					}   
				 },
					 				//'format'=> ['image', ['width' => 200, 'height' => 473 ]],
					
			],
            'description',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
	
	echo "<h1> Listing without the Gridview <h1/>";
	
	if (count($dataProvider->getModels())) {
		$imagesPerRow = 5;
		$i = 1;
		$models = $dataProvider->getModels(); 
		foreach($models as $key=>$value){ 
			?> 
			<img src="<?= Yii::$app->homeUrl  . "uploads/"   . $value->filename;?>" height='100' width='auto'>
			<?php 
			if ($i % $imagesPerRow == 0) {
				echo "<br>";
			}
			$i++;
		} 
	} 
	
	
	?>	
	
    <?php Pjax::end(); ?>
</div>
