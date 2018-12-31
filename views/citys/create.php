<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Citys */

$this->title = Yii::t('app', 'Create Citys');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Citys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="citys-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'state'   => null,	//we are starting with a clean sheet with no preselected state or county
		'county'  => null,
		'dataProviderCitys'  => $dataProviderCitys,
    ]) ?>

</div>
