<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\PhoneMakes;
/* @var $this yii\web\View */
/* @var $model app\models\PhoneModels */

$this->title = $model->model;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Phone Makes'), 'url' => ['phone-makes/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app',$model->phoneMakes->make. " " . 'Phone Models'), 'url' => ['index', 'id' => $model->phone_make_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phone-models-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id, 'make' => $model->phone_make_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'id'=> 'phone-models-view-detail-view',
		'model' => $model,
        'attributes' => [
            //'id',
            'model',
			[
				'label' =>"Phone Make",
				'attribute' => 'Phone Make',	
				'value'=> function($model){
					return ($model->phoneMakes->make);
				}
			],
            //'phone_make_id',
        ],
    ]) ?>

</div>
