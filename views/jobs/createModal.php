<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Branches */

$this->title = Yii::t('app', 'Make Payments');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branches-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formModal', [
        'model' => $model,
		'job' => $job,
    ]) ?>

</div>
