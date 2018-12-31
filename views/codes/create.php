<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Codes */

$this->title = 'Create Codes';
$this->params['breadcrumbs'][] = ['label' => 'Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="codes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
