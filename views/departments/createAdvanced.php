<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Departments */

$this->title = Yii::t('app', 'Create Departments');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Departments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="departments-create">
<b>Dependant Dropdowns - using php/javascript/Ajax functions </b>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formAdvanced', [
        'model' => $model,
    ]) ?>

</div>
