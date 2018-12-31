<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CompaniesFiles */

$this->title = 'Create Companies Files';
$this->params['breadcrumbs'][] = ['label' => 'Companies Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="companies-files-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
