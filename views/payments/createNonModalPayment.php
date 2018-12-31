<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\payments */
$this->title = Yii::t('app', "Payments: $subtitle");//. $payments->amount > 0 ? "Total Payments: " . $payments->amount: NULL
/////$this->title = Yii::t('app', "Payments: $payments - Balance: $balance");//. $payments->amount > 0 ? "Total Payments: " . $payments->amount: NULL
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payments-create">

    <h1><?= "Make Payments:: Job # $job"; ?></h1>

    <?= $this->render('_formcreateNonModalPayment', [
        'model' => $model,
		'job' => $job,
		/////'charges' => $charges,
		//'payments' => $payments,
    ]) ?>

</div>
