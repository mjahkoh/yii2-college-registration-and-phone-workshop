<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StudentGames */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Student Games',
]) ;//. $model->id
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Games'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="student-games-update">

    <h1><div id="title"><?= Html::encode($this->title) ?></h1></div>

    <?= $this->render('_form', [
        'dataProvider' => $dataProvider,
		'model' => $model,
    ]) ?>

</div>
