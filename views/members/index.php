<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\helpers\Setup;
use yii\helpers\Url ;
/* @var $this yii\web\View */
/* @var $searchModel app\models\MembersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//print_r( $searchModel); exit;
$this->title = Yii::t('app', $searchModel->personnelCategorys[$id]);
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="members-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<p> Showcases: Dynamic dropdownsLists, Dependant dropdownlists,  Static dropdownlists, Option Box, ListBox, Kartik Date Picker and Checkbox<br />

NB.You cannot change the username or email. The password can only be resetted in <a href="<?= Url::toRoute(["/index.php/members/request-password-reset"  ]);?>">changePassword</a> and the <a href="<?= Url::toRoute(["/index.php/members/set-admin-password"  ]);?>">Admin password</a> must be set on <a href="<?= Url::toRoute(["/index.php/site/initialize"  ]);?>">intitialization</a> and the serial code entered. The default Username is admin and Password is admin<br />
In utilities check the Unbiased random password generator derived from Taylor Hornby. An email is sent to the Email provided to verify the serial codes if their is an internet connection and will always check whether their is a connection on initialisation if the serial number is unverified. The remote database should have the serial numbers to check against while the  local database have the codes encoded (Setup::encode). If the serial number is legitimate codeverified is flaged. If the serial is validated on the remote database , code_valid is flagged , both are in Companies model.<br />
Three scenarios are envisaged, Personnel , students and Update. In update scenario you cannot change the password neither email. It also shows how to retrieve information from table and display it on the form via ajax (_form.php) using javascript val function.
</p>

    <p>
        <?= Html::a(Yii::t('app', 'Create '.$searchModel->personnelCategorys[$id]), ['create', 'id'=> $id], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'sex',
            //'marital_status',
			'fullName',
			'nickname',
	           /*
			   [
	               'label' =>"Phone",
	               'attribute' => 'mobile',
	               'value'=>function($model){
	                   return Setup::convertTel($model->mobile);
	               }
	           ],*/
	           [
	               'label' =>"Phone",
	               'attribute' => 'mobile',
	               'value'=>function($model){
	                   return Setup::convertTel($model->mobile);
	               }
	           ],			
            //'mobile',
            'username',
            // 'email:email',
            // 'state',
            // 'city',
            
            // 'pets_name',
            // 'favourite_companion',
            // 'password_hash',
            // 'auth_key',
            // 'home_phone',
            // 'savings',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?>

</div>
