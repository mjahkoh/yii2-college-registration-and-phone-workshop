<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Citys */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Citys',
]) . $model->city;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Citys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->city_id, 'url' => ['view', 'id' => $model->city_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="citys-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'   => $model,
		'state'   => $model->countys->state_id,
		'county'  => $model->county_id,
    ]) ?>

</div>
