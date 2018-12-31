<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Url ;

use app\models\Members;
use app\models\Games;
use app\models\StudentGames;
/* @var $this yii\web\View */
/* @var $model app\models\StudentGames */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="student-games-form">

    <?php Pjax::begin(['id' => 'new_student_games']);  ?>

		<?php
		/*
		$games = ArrayHelper:: map (Games::find()->orderBy('game ASC')-> all() , 'id' , 'game' ) ;
		 echo $form->field($model,'gamesid')->dropDownList(
			$games ,
			[
				'prompt' => 'Choose Game',
				
			]) ; 
			*/
		?>
		<?php
		
		$form = ActiveForm::begin(['options' => ['data-pjax' => true ]]);
		//populate members
		/**/
		if ($model->isNewRecord) {
			//if new record omit students who have their games uploaded
			/*
			$sql = "select concat(members.firstname, ' ', members.middlename, ' ', members.surname) AS fullname,
			id as studentid from members where id not in (select studentid from student_games ) order by firstname, middlename,surname
			";*/
			$subQuery = StudentGames::find()->select('studentid')->groupBy('studentid');
			$query = Members::find()
			->where(['not in', 'id', $subQuery])
			->orderBy('firstname, middlename, surname ASC');
		} else {
			/*
			$sql = "SELECT select concat(members.firstname, ' ', members.middlename, ' ', members.surname) AS fullname, members.id as studentid
			 FROM   student_games  INNER JOIN members ON (student_games.studentid = members.id)
			order by firstname, middlename, surname)
			";*/
			$query = Members::find()
			->orderBy('firstname, middlename, surname ASC');
		}
		
		//var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
		$theQuery = $query->all();
		$members = ArrayHelper:: map ($theQuery , 'id' , 'fullName') ;
		echo $form->field($model,'studentid')->dropDownList(
			$members ,
			[
				'prompt' => 'Choose Student',
				'value' => $model->studentid
			]
		) ; 
		?>
	
		<?php	
		if ($model->isNewRecord){	
		
			echo GridView::widget([
			   'dataProvider' => $dataProvider,
			   'id' => 'membersGamesPlayedGridView',
			   'columns' => [
					//'id',
					   [
						   'label' =>"Game Playes",
						   'attribute' => 'game',
						   'value'=>function($model){
							   return $model["game"];
						   }
					   ],			
					[
						'class' => 'yii\grid\CheckboxColumn',
							'header' => Html::checkBox('selection_all', false, [
								'class' => 'select-on-check-all',
								'label' => 'Check All',
								]),
						'checkboxOptions' => function ($model, $key, $index, $column){
							return [ 
								//"value" => 	 $model->id,
								"checked" => 0
						   
							];
						}
					],
					//['class' => 'yii\grid\ActionColumn'],
				],
		]);
		
		} else {	//update mode
		   
		   /* if the sudent didnt register any games hide gridview*/
		   
		   
				echo GridView::widget([
					   'showOnEmpty'=> false,
					   'dataProvider' => $dataProvider,
					   'id' => 'membersGamesPlayedGridView',
					   'columns' => [
							'id',
							   [
								   'label' =>"Game Playes",
								   'attribute' => 'game',
								   'value'=>function($data){
									   return $data["game"];
								   }
							   ],			
							[
								'class' => 'yii\grid\CheckboxColumn',
									'header' => Html::checkBox('selection_all', false, [
										'class' => 'select-on-check-all',
										'label' => 'Check All',
										]),
								'checkboxOptions' => function ($data, $key, $index, $column){
									return [ 
										"value" => 	 $data['id'],//	function($data){return $data["id"] ;},//below $data['gamesid']
										"checked" => $data['gamesid'],
								   
									];
								}
							],
							//['class' => 'yii\grid\ActionColumn'],
						],
				]);
		  
		}
		
		
		
?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	<?php 
	ActiveForm::end();
    Pjax::end();  ?>

</div>


<?php
$isNewRecord = $model->isNewRecord ? 1 : 0 ;
if (isset($_GET['id'])) {
	$studentid = $_GET['id'];
	//echo "studentid--:: $studentid<br>";
} else {
	$studentid = 0;
}
echo "isNewRecord:: $isNewRecord<br>";
print_r($_GET);
$this->registerJs(
"
$(document). ready (function (){

	var isNewRecord = $isNewRecord;
	var studentid = $studentid;

	$('select').on('change', function(e){//, 'load',
		  console.log('changed');
		  console.log('this valL:' + this.id);
		  console.log('this valL:' + this.value);
		  var id = this.value;
		  var dataObj = {
				'id':this.value, 
		  };
		  e.preventDefault();
		  $.pjax({
			type: 'GET',
			url			: '" . Url::toRoute(["student-games/update-student"]). "',
			container: '#new_student_games',
			data: dataObj,
			dataType: 'application/json'
		  })
	});
	
	
	
	if (isNewRecord == 1) {
		if (studentid > 0) {
			$('#studentgames-studentid').val(studentid);
			console.log('studentid->>::' + studentid);
			var title ='Create Student games';
		}
		console.log('isnewrecord:::' + isNewRecord);
	} else {
		console.log('update');
		console.log('studentid' + studentid);
		var studentid = $studentid;
		var selected = $('#studentgames-studentid').val();
		if (selected == '') {	//select the student id passed in the url
			$('#student-games-studentid').val(studentid);
		}
		var title ='Update Student games';
		//$('#title').attr('h1');
	}
	$('#title').text(title);
	$('#title').attr('h1');

});
"
);
?>
