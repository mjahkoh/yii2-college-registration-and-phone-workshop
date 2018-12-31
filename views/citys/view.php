<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Citys */

$this->title = $model->city_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Citys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="citys-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->city_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->city_id], [
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
            'city_id',
            'city',
            'city_ascii',
            //'county_id',
			[
				'label' =>"State",
				'attribute' => 'county_id',	
				'value'=> $model->countys->states->state_name,
			],
			[
				'label' =>"County",
				'attribute' => 'county_id',	
				'value'=> $model->countys->county,
			],
        ],
    ]) ?>

</div>
