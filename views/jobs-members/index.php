<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\helpers\Setup;
use yii\helpers\Url ;
use app\models\JobsMembers;
/* @var $this yii\web\View */
/* @var $searchModel app\models\MembersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Members');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jobs-members-index">

<p>Saves all the users of the jobs model which includes staff and clients.<br />
				By default, the loginForm uses the Jobs Model for logging purposes. To change that to Members Model amend the web configuration file (/config/web.php), Components section, user -> identityClass  and also LoginForm Model. Replace all occurences of the JobsMembers model to Members
</p>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <p>
        <?= Html::a(Yii::t('app', 'Create Member'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'id'=> 'members-index-gridview',
	    'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
				'attribute'	=>'name',
				'format' => 'raw',
				'value' => function ($model, $key, $index) {
                	return Html::a($model->name, ['jobs/index-jobs-per-client', 'member' => $model->id] );		
				},
			],

            [
				'attribute'	=>'tel',
				'value' => function ($model) {
					return ('+' . $model->tel);
				}
			],
            [
				'attribute'	=>'mobile',
				'value' => function ($model) {
					return ($model->mobile ? '+' . $model->mobile : NULL);
				}
			],
            [
				'attribute'	=>'status',
				'value' => function ($model) {
					if ($model->status === JobsMembers::STATUS_ACTIVE) {
						$status = "Active";
					} elseif ($model->status === JobsMembers::STATUS_INACTIVE) {
						$status = "Dormant";
					} else {
						$status = "Deleted";
					}
					return ($status);
				}
			],
            [
				'attribute'	=>'category',
				'value' => function ($model) {
					return ($model->category == JobsMembers::STAFF_MEMBER ? 'Staff Member' : 'Clientelle' );
				}
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?>

</div>
