<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\helpers\Setup;
use yii\helpers\Url ;
/* @var $this yii\web\View */
/* @var $searchModel app\models\MembersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Members');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<p>Lists all Members - Select the Member to view and allocate rights to from the Gridview<br />


</p>

    <p>
        <?php //Html::a(Yii::t('app', 'Create Member'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'sex',
            //'marital_status',
            [
				'attribute'	=>'fullName',
				'format' => 'raw',
				'value' => function ($model) {
					$url = Url::to([
					'/rbac/rights-allocation',
					'id' =>$model->id]);
					$options = [];
					return Html::a($model->fullName, $url, $options);
				}
			],
			//'nickname',
	           [
	               'label' =>"Phone",
	               'attribute' => 'mobile',
	               'value'=>function($model){
	                   return Setup::convertTel($model->mobile);
	               }
	           ],			
            //'mobile',
            'username',
            // 'email:email',
            // 'state',
            // 'city',
            
            // 'pets_name',
            // 'favourite_companion',
            // 'password_hash',
            // 'auth_key',
            // 'home_phone',
            // 'savings',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?>

</div>
