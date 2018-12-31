<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PhoneMakes */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Phone Makes',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Phone Makes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="phone-makes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
