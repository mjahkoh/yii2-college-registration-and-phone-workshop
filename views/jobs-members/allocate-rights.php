<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\JobsMembers;
use yii\helpers\Url ;
use app\helpers\Setup;

$this->title = Yii::t('app', 'Members');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="allocate-rights-form">
<h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('app', 'Create Member'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

		<div style="width: 100%; overflow: hidden;">
			<div style="width: 600px; float: left;">
			<div style="width: 600px; display: table-cell;"> 

				<?php Pjax::begin(); ?>    
				<?= GridView::widget([
						'dataProvider' => $dataProvider,
						//'filterModel' => $searchModel,
						'columns' => [
							['class' => 'yii\grid\SerialColumn'],
							[
								'attribute'	=>'fullName',
								'format' => 'raw',
								'label'		=>'Name',
								'value' => function ($model) {
									$url = Url::to([
									'/jobs-members/allocate-rights',
									'id' =>$model->id]);
									$options = [];
									return Html::a($model->fullName, $url, $options);
								}
							],
							//['class' => 'yii\grid\ActionColumn'],
						],
					]); ?>
				<?php Pjax::end(); ?>

			</div>
			<div class="help-block"></div></div>
			<div style="margin-left: 620px;"> <div style="display: table-cell;"> 
				<?= GridView::widget([
						'dataProvider' => $dataProvider,
						//'filterModel' => $searchModel,
						'columns' => [
							['class' => 'yii\grid\SerialColumn'],
							[
								'attribute'	=>'fullName',
								'format' => 'raw',
								'label'		=>'Name',
								'value' => function ($model) {
									$url = Url::to([
									'/jobs-members/allocate-rights',
									'id' =>$model->id]);
									$options = [];
									return Html::a($model->fullName, $url, $options);
								}
							],
							//['class' => 'yii\grid\ActionColumn'],
						],
					]); ?>
			</div>
			<div class="help-block"></div></div>
		</div>
	
</div>


<?php
$array = ['SiteController.php','AuthorsController.php'];
print_r(count($array));
echo "<br>";
print_r(Setup::actionGetcontrollersandactions($array)) ;


?>