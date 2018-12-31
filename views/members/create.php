<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Members */

//$this->title = Yii::t('app', 'Register Student');
$this->title = Yii::t('app', $model->scenario === 'students' ? 'Register Student' : 'Register Personnel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="members-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'id' => $id,
		'authItems' => $authItems,
		'dataProvider' => $dataProvider,
		//'games' => $games,
    ]) ?>

</div>
