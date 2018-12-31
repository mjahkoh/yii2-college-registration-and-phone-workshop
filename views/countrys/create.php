<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Countrys */

$this->title = 'Create Countrys';
$this->params['breadcrumbs'][] = ['label' => 'Countrys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="countrys-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
