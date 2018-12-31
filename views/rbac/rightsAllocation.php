<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\helpers\Setup;
use yii\helpers\Url ;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MembersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Assign Rights');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index-members']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-index">

    <h1><?= Html::encode($this->title) .' to ' . $model->fullname;?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<p>Lists all Members<br />


</p>

    <p>
        <?php //Html::a(Yii::t('app', 'Create Member'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


<?php 
	Pjax::begin();   
	//echo "id: $id<br>";exit;
	//$memberId = $id;
	$form = ActiveForm::begin(['id'=> 'form-RightsAllocation']);?>  
		<?= GridView::widget([
				'dataProvider' => $dataProvider,
				//'filterModel' => $searchModel,
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
					[
					   'attribute' => 'role',
					   'value' => function ($model, $key, $index, $column){
							return ucfirst($model['role']);
					   },
					],	
					
					[
						//'attribute' => 'index',
						'class' => 'yii\grid\CheckboxColumn',
						'header' => Html::checkBox('index', false, [
								'class' => 'checkAll',
								'label' => 'Index',
								]),
						'checkboxOptions' => function ($model, $key, $index, $column) use ($id) {
							//print_r($column);exit;
							$checked = Yii::$app->authManager->checkAccess($id, $model['index']) ? true : false;
							return [ 
								"value" => 	 $model['index'],	//Yii::$app->user->can($model['index']),
						   		"checked" => $checked,
							];
						}
					],
		
					[
						//'attribute' => 'create',
						'class' => 'yii\grid\CheckboxColumn',
						'header' => Html::checkBox('create', false, [
								'class' => 'checkAll',		//select-on-check-all
								'label' => 'Create',
								]),
						'checkboxOptions' => function ($model, $key, $index, $column) use ($id) {
							$checked = Yii::$app->authManager->checkAccess($id, $model['create']) ? true : false;
							return [ 
								"value" => 	$model['create'],
								"checked" => $checked,
						   
							];
						}
					],
		
					[
						//'attribute' => 'update',
						'class' => 'yii\grid\CheckboxColumn',
						'header' => Html::checkBox('update', false, [
								'class' => 'checkAll',
								'label' => 'Update',
								]),
						'checkboxOptions' => function ($model, $key, $index, $column) use ($id) {
							$checked = Yii::$app->authManager->checkAccess($id, $model['update']) ? true : false;
							return [ 
								"value" => 	 $model['update'],
								"checked" => $checked,
						   
							];
						}
					],
		
					[
						//'attribute' => 'view',
						'class' => 'yii\grid\CheckboxColumn',
						'header' => Html::checkBox('selection_all', false, [
								'class' => 'checkAll',
								'label' => 'View',
								]),
						'checkboxOptions' => function ($model, $key, $index, $column) use ($id) {
							$checked = Yii::$app->authManager->checkAccess($id, $model['view']) ? true : false;
							return [ 
								"value" => 	 $model['view'],
								"checked" => $checked,
						   
							];
						}
					],
		
					[
						//'attribute' => 'delete',
						'class' => 'yii\grid\CheckboxColumn',
						'header' => Html::checkBox('selection_all', false, [
								'class' => 'checkAll',
								'label' => 'Delete',
								]),
						'checkboxOptions' => function ($model, $key, $index, $column) use ($id) {
							$checked = Yii::$app->authManager->checkAccess($id, $model['delete']) ? true : false;
							return [ 
								"value" => 	 $model['delete'],
								"checked" => $checked,
						   
							];
						}
					],
							
					// ['class' => 'yii\grid\ActionColumn'],
				],
			]); 
			
			// Add other fields if needed or render your submit button
			echo '<div class="text-right">' . 
				 Html::submitButton('Submit', ['class'=>'btn btn-primary']) .
				 '<div>';
			
		ActiveForm::end();	
Pjax::end(); 
 ?>

</div>
<?php

$this->registerJs(
"

$(document). ready (function (){

	$('.checkAll').click(function () {
		
		var checked = $(this).prop('checked');
		console.log(checked);
		
		var index = $(this).parent().index();
		console.log(index);
		
		$('tr').each(function(i, val){
			$(val).children().eq(index).children('input[type=checkbox]').prop('checked', checked);
		});
		
		//console.log(columnIndex);
		
	});

		
});

"
);

?>