<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UnitsBookedByStudents */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Units Booked By Students'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="units-booked-by-students-view">

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
            //'id',
            'units.unit',
			['attribute' => 'semester',	'value'=> $model->semester ==1 ? "I" : $model->semester ==2 ? "II" : "III"],//
            'academic_year',
			['attribute' => 'student_id',	
				'value'=> $model->members->firstname . ' ' . $model->members->middlename . ' ' . $model->members->surname],//
			//['attribute' => 'phone',	'value'=> "0".substr($model->phone,0,3)."-".substr($model->phone,3,10),],
        ],
    ]) ?>

</div>
