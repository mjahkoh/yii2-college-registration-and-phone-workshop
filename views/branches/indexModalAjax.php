<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use dosamigos\datePicker\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BranchesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Branches');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branches-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<p>Creates a Branch using a Bootstrap Modal and submits the form using Pjax, Ajax and ActiveForm - Example 1<br />
Default sorting by Branch_name (SORT_DESC), date_created (SORT_ASC) and searching on related data, Grid Filtering by Static drop down
</p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<p> 
<!-- index.php/branches/
-->
<?= Html::button(Yii::t('app', 'Create Branch'),
 [
	 'value'=>Url::toRoute('create-modal-ajax'),
	 'class' => 'btn btn-success', 
	 'id'=>'modalButton',
 ])
 ; ?>
 </p>	
 
<?php 
	Modal::begin([
		'header'		=>	'<h4>Branches</h4>', 
		'id'			=>	'modal',
		'size'			=>	'modal-lg',//sm
		//'clientOptions' => ['backdrop' => 'static', 'keyboard' => false] ,
		]);
		echo "<div id='modalContent'></div>";
	Modal::end();
?>	

<?php Pjax::begin([
	'id'=>'pjax-container',
	'enablePushState' => false,
]); ?>    

<?= $this->render('indexGridView', [
	'searchModel' => $searchModel,
	'dataProvider' => $dataProvider,
]) ?>
	
<?php Pjax::end(); ?></div>
	
</div>
