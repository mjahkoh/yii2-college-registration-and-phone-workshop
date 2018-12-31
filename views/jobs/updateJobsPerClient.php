<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Jobs */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Jobs',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jobs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="jobs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formJobsPerClient', [
        'model' => $model,
		'member' => $member ,
    ]) ?>

</div>
