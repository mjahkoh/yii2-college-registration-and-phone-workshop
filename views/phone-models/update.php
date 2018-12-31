<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PhoneModels */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Phone Model',
]) . $modelphonemakes->make . ' - ' . $model->model;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Phone Makes'), 'url' => ['phone-makes/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $modelphonemakes->make . " " .'Phone Models'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="phone-models-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'modelphonemakes' => $modelphonemakes,
    ]) ?>

</div>
