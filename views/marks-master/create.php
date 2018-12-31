<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MarksMaster */

$this->title = Yii::t('app', 'Create Marks Master');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Marks Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marks-master-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
