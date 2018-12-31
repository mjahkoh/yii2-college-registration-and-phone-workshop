<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CompaniesFiles */

$this->title = 'Update Companies Files: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Companies Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="companies-files-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
