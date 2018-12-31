<?php

use yii\helpers\Html;
use app\models\JobsMembers;

/* @var $this yii\web\View */
/* @var $model app\models\JobsMembers */
/*
$category = "Create Member";
if ($id != JobsMembers::STAFF_MEMBER) {
	$category = "Create Clientelle";
} 
*/
$this->title = Yii::t('app', 'Create Clientelle' );
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'authItems' => $authItems,
		'id' => $id,
    ]) ?>

</div>
