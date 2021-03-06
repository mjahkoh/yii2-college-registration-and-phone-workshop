<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Branches */

$this->title = Yii::t('app', 'Create Branches');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Branches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branches-create">

    <h1><?= Html::encode($this->title) ?></h1>
	<p>Creates a Branch using a Bootstrap Modal</p>
    <?= $this->render('_formModal', [
        'model' => $model,
    ]) ?>

</div>
