<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Units */

$this->title = Yii::t('app', 'Create Units');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Units'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="units-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
