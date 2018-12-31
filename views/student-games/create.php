<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\StudentGames */

$this->title = Yii::t('app', 'Create Student Games');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Games'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-games-create">

    <div id="title"><h1><?= Html::encode($this->title) ?></h1></div>
    <?= $this->render('_form', [
        'model' => $model,
		'dataProvider' => $dataProvider,
		
    ]) ?>

</div>
