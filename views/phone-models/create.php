<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PhoneModels */

//$this->title = Yii::t('app', 'Create Phone Models');
$this->title = Yii::t('app', 'Create '. $modelphonemakes->make . " Model");
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Phone Makes'), 'url' => ['phone-makes/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Phone Models'), 'url' => ['index', 'id' => $modelphonemakes->id]];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phone-models-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'modelphonemakes'  => $modelphonemakes,
    ]) ?>

</div>
