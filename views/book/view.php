<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Books'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
            'name',
			[
				'label' =>"Author",
				'attribute' => 'author_id',	
				'value'=> $model->authors->name,
			],
			'book_code',
            'synopsis:ntext',
            'color',
			[
				'label' =>"Publish Date",
				'attribute' => 'publish_date',	
				'format' => ['date', 'php:d-M-Y']
			],
            'sell_amount',
            'buy_amount',
			[
				'label' =>"Status Date",
				'attribute' => 'status',	
				'value' => function ($model) {   
					return ($model->status == 1) ? "Active" : "Dormant";
				}
			],
        ],
    ]) ?>

</div>
