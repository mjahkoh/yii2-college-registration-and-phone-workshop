<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\JobsMembers */

//$this->title = Yii::t('app', 'Register Student');
$this->title = Yii::t('app', $model->scenario === 'students' ? 'Register Student' : 'Register Personnel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="jobs-members-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'authItems' => $authItems,
		'id' => $id,
    ]) ?>

</div>
