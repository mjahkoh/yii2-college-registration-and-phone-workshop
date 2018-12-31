<?php

use yii\helpers\Html;
use app\helpers\Setup;
/* @var $this yii\web\View */
/* @var $searchModel app\models\MembersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Initialise Rights');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



	<?= Html::beginForm(['init'], 'post');?>
	
	
		<div class="form-group">
			<p>
        <?= Html::submitButton(Yii::t('app', 'Initialise Rights Allocation'), ['class' => 'btn btn-success' ]) ?>
			</p>
			<?php //Html::submitButton('POST', ['class' => 'btn btn-primary']) ?>
		</div>
	<?php Html::endForm() ?>
</div>