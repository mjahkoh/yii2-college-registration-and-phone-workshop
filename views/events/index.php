<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $searchModel app\models\EventsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Events';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-index">
<p>Events Organizer - Click the Grid and enter the details from the popup. Scroll left or right the view the events already entered </p>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php 
	Modal::begin([
		'header'		=>	'<h4>Events</h4>', 
		'id'			=>	'modal',
		'size'			=>	'modal-lg',//sm
		//'clientOptions' => ['backdrop' => 'static', 'keyboard' => false] ,
		]);
		echo "<div id='modalContent'></div>";
	Modal::end();
?>	


<?= \yii2fullcalendar\yii2fullcalendar::widget(array(
      'events' => $events,
     ));
?>
<?php Pjax::end(); ?>
</div>
