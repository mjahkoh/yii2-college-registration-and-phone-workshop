<?php

namespace app\components;

use yii\base\Component;
use Yii;
use yii\web\Controller;
use yii\helpers\Url ;
use yii\web\Response;
use app\helpers\Setup;
use app\models\Members;
use app\models\AdminPasswordForm;

class InitClass extends \yii\base\Component{

	public function init() {
        
        parent::init();
    }
	
	/**/
	public function registerComponents(){
			/*
			\Yii::$app->setComponents([
	                'sms' => [
							'class'=>'abhimanyu\sms\components\Sms',
							'transportType' => 'smtp',
							'transportOptions' => [
								'host' 			=> '$transportoptionshost',
								'username'		=> '$transportoptionsusername',
								'password' 		=> '$transportoptionspassword',
								'port' 			=> '$transportoptionsport',
								'encryption' 	=> '$transportoptionsencryption'
							],
	                ]
					'user' => [
							'class'=>'yii\web\User',
							'identityClass' => 'app\models\Members',
							'enableAutoLogin' => false,
							'loginUrl'=>Url::to(["/admin/dashboard/login"])
					],
					
			]);
			*/
			
	}

}
