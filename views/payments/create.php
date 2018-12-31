<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\payments */
$charges = isset($jobcharges) ? $jobcharges : 0;
$pays = isset($payments['amount']) ? $payments['amount'] : 0 ;
$bal = $charges - $pays;
$balance =   number_format($bal,2);
$payments =   number_format($pays,2);
$this->title = Yii::t('app', "Payments:$payments - Balance:$balance");//. $payments->amount > 0 ? "Total Payments: " . $payments->amount: NULL
/////$this->title = Yii::t('app', "Payments: $payments - Balance: $balance");//. $payments->amount > 0 ? "Total Payments: " . $payments->amount: NULL
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payments-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->renderAjax('_form', [
        'model' => $model,
		'job' => $job,
		/////'charges' => $charges,
		'payments' => $payments,
    ]) ?>

</div>
