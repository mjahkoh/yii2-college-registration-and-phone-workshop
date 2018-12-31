<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Images */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="images-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
			'description',
			[
				'label' =>"File location",
				'attribute' => 'filelocation',	
				'value' => Yii::getAlias('@uploads') . $model->filename,
			],
			[
				'label' =>"Image",
				'attribute' => 'filename',	
				'format'=> 'raw',
				'value' => function ($model) {   
					if ($model->filename!='') {
					  return '<img src ="' . Yii::$app->homeUrl  . "uploads/" . $model->filename . '" height="100" width="auto"' .   '>';
					} else {
						return 'no image';
					}   
				 },
					 				//'format'=> ['image', ['width' => 200, 'height' => 473 ]],
					
			],
        ],
    ]) ?>

</div>
