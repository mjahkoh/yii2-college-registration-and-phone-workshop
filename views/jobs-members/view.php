<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\JobsMembers;
/* @var $this yii\web\View */
/* @var $model app\models\JobsMembers */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-view">

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
        'id'=> 'members-view-detail-view',
		'model' => $model,
        'attributes' => [
            'id',
            'name',
			[
				'label' =>"Status",
				'attribute' => 'status',	
				'value'=> 
					function($model){
						return ($model->status == JobsMembers::STATUS_ACTIVE ? 'Active' : $model->status == JobsMembers::STATUS_INACTIVE ? "In Active" : "Deleted" ) ;  
					}
			],
			[
				'label' =>"Tel",
				'attribute' => 'tel',	
				'value'=> 
					function($model){
						return ($model->tel_prefix . $model->tel) ;  
					}
			],
			[
				'label' =>"Mobile",
				'attribute' => 'mobile',	
				'value'=> 
					function($model){
						return ($model->mobile_prefix . $model->mobile) ;  
					}
			],
			[
				'label' =>"Category",
				'attribute' => 'category',	
				//'value'=> Yii::$app->formatter->asDatetime($model->start_date),
				'value'=> 
					function($model){
						return ($model->category == JobsMembers::STAFF_MEMBER ? 'Staff Member' : 'Clientelle' ) ; 
					}
			],
        ],
    ]) ?>

</div>
