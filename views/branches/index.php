<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dosamigos\datePicker\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BranchesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Branches');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branches-index">
<b>Default sorting by Branch_name (SORT_DESC), date_created (SORT_ASC) and searching on related data, Grid Filtering by Static drop down and Date picker</b>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?= Html::a(Yii::t('app', 'Create Branches'), ['create'], ['class' => 'btn btn-success']) ?>

		<?= $this->render('indexGridView', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
		]) ?>

	
</div>
