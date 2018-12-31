<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UnitsBookedByStudents */

$this->title = Yii::t('app', 'Book a Unit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Units Booked By Students'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="units-booked-by-students-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
