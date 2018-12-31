<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\MarksMaster */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Marks Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marks-master-view">

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

<?php 
//print_r($model); exit;
?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
			[
				'label' =>"Lecturer",
				'attribute' => 'members_id',	
				'value' => function ($model) {
					return ($model->members->fullName);
				}
			],
			[
				'label' =>"Date Of Exam",
				'attribute' => 'date_of_exam',	
				//'value'=> Yii::$app->formatter->asDatetime($model->end_date),
				'format' => ['date', 'php:d-M-Y']
			],
            'units.unit',
            'total_marks',
            'class',
        ],
    ]) ?>
	
	<!-- marks_details-->
<?php
	$form = ActiveForm::begin(['id' => 'marksMaster',]); 
	$attribs = $searchModel->formAttribs;
	echo TabularForm::widget([
		'dataProvider'=>$dataProvider,
		'form'=>$form,
		'attributes'=>$attribs,
		'gridSettings'=>[
			'floatHeader'=>true,
			'panel'=>[
				'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Manage Score</h3>',
				'type' => GridView::TYPE_PRIMARY,
				'after'=> Html::a('<i class="glyphicon glyphicon-plus"></i> Add New', '#', ['class'=>'btn btn-success']) . ' ' . 
						Html::a('<i class="glyphicon glyphicon-remove"></i> Delete', '#', ['class'=>'btn btn-danger']) . ' ' .
						Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Save', ['class'=>'btn btn-primary'])
			]
		]	
	]);
	ActiveForm::end();
?>	

</div>
<!-- ternary
						 $Myprovince = (
						 ($province == 6) ? "city-1" :
						  (($province == 7) ? "city-2" :
						   (($province == 8) ? "city-3" :
							(($province == 30) ? "city-4" : "out of borders")))
						 );
-->