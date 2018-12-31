<?php

namespace app\components;
use Yii;
//use yii\web\Controller;

use app\models\Members;

class checkIfInitialised extends \yii\base\Behavior
{

	public function  events() {
		return [
			\yii\web\Application::EVENT_BEFORE_REQUEST => 'checkIfInitialised',
		];
	}

	/*
	public function  checkIfLoggedIn() {
		
		if (Yii::$app->getUser()->isGuest) {
			return $this->redirect(['/site/login']);
		}
	}
	*/
	
	public function  checkIfInitialised() {
		
		//any code that you need to check
		//if (!Yii::$app->user->getIsGuest()  && Yii::$app->user->identity->username === 'admin' && Members::isAdminInitialised() == false) {
			//return Yii::$app->response->redirect(['members/set-admin-password']);
		//}
		
	}
	
	
}

?>