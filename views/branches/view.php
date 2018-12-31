<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\Setup;

/* @var $this yii\web\View */
/* @var $model app\models\Branches */

$this->title = $model->branch_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Branches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branches-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
	
<?php //echo "<div class='alert alert-success'>".Yii::$app->session->getFlash('success')."</div>"; ?>
	
	
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
            //'id',
            'branch_name',
			[
				'label' =>"Company",
				'attribute' => 'companies_company_id',	
				'value'=> $model->companies->company_name,
			],
            'address',
			['attribute' => 'date_created',	'value'=> Yii::$app->formatter->asDatetime($model->date_created),],
			['attribute' => 'status',	'value'=> $model->status ==1 ? "Active" : "Dormant"],
            'location',
        ],
    ]) ?>

</div>
