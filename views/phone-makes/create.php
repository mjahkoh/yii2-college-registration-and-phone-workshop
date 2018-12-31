<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PhoneMakes */

$this->title = Yii::t('app', 'Create Phone Makes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Phone Makes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phone-makes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
