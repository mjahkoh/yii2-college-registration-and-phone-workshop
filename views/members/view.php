<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\Setup;
use app\models\States;
use app\models\Members;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\Members */

$this->title = $model->fullName ;
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
        'model' => $model,
        'attributes' => [
            'id',
			'firstname',
			'middlename',
			'surname',
			[
				'label' =>"Date Of Birth",
				'attribute' => 'date_of_birth',	
				'format' => ['date', 'php:d-M-Y']
			],
			['attribute' => 'sex',	'value'=> $model->sex ==1 ? "Male" : "Female"],
            ['attribute' => 'marital_status',	'value'=> $model->maritalstatus[$model->marital_status] ],
			['attribute' => 'mobile',	'value'=>Setup::convertTel($model->mobile)],
			['attribute' => 'home_phone',	'value'=>Setup::convertTel($model->home_phone)],
            'username',
            'email:email',
			[
				'label' =>"State",
				'attribute' => 'state',	
				'value'=> 
					function($model){
						return ($model->states->state_name) ;  
					}
			],
            'countys.county',
			'citys.city',
            'nickname',
            //'pets_name',
            //'favourite_companion',
            /////['attribute' => 'category',	'value'=> Members::personnelCategorys[$model->category]],
            //'password_hash',
            //'auth_key',
			/////['attribute' => 'savings',	'value'=> $model->savings ==1 ? "I Play" : "I dont Play Games"],
        ],
    ]) ?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'games.game',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
<?php


?>