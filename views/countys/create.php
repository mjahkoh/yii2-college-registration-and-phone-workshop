<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Countys */

$this->title = Yii::t('app', 'Create Countys');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Countys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="countys-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
