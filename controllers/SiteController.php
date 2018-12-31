<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Members;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout', 'language','changepassword'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
		$this->layout = 'loginLayout';
		
        if (!Yii::$app->user->isGuest) {
            //print_r('not guest' );
			return $this->goHome();
        }

        $model = new LoginForm();
		
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
           
		    if ( Yii::$app->user->identity->username === 'admin') {
				//echo "admin<br>";
				//$initialised = Members::isAdminInitialised();
				//echo "initialised:: ".Members::isAdminInitialised()."<br>";exit;
				if (Members::isAdminInitialised() == false) {
					return $this->redirect(['members/set-admin-password']);
				}	
				
				/*
					1.check wether the admin has entered the code. if not prompt and update code field in companies model.
					check wether the code entered is in the codes table . if so flag code_valid to true 
					2. if code is entered , try and verify  online (siliconmedia online db) wether its valid. 
					if valid set code_verified to true
				*/
				if ($this->checkCode() == false){
					//$codemodel = new CodeForm();
					return $this->redirect(['members/set-code']);
				} else {
					/*
					since the serial is entered, now connect online to 
					silicon media and flag code_valid (companies model) depending on 
					wether the serial is valid and registered for that user
					*/
					
				}

			}
			if ($model->hasErrors()) {
				//print_r($model->getErrors());
				//echo ' failure';
			}		
			return $this->goBack();
        } else {
			if ($model->hasErrors()) {
				//print_r($model->getErrors());
				//echo 'dsfsdf22 failure';
			}		
		}
		/**/
		return $this->render('login', [
			'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
	
	/*
	checks wether the code is entered in the companies table
	*/
	
	public function checkCode() {
		$sql = "select id from companies where code_verified = 1 limit 1";
		$check = Yii::$app->db->createCommand($sql)->queryOne();
		if ($check['id']) {
			//print_r('code verified');
			return true;
		}
			//print_r('code not verified');
		return false;
	}	

	
    /**
     * Login action.
     *
     * @return string
     */
    public function actionLanguage()
    {
        if (isset($_POST['lang'])) {
            Yii::$app->language = $_POST['lang'];
			$cookie = new yii\web\cookie([
				'name' => 'lang',
				'value' => $_POST['lang'],
			]);
			Yii::$app->getResponse()->getCookies()->add($cookie);
			//print_r($cookie);
        } else {
			//echo "zii";
		}
		//exit;

    }


	public function actionInitialize() {
		//$sql = "delete from `companies` where id <> 1";
		$sql = "update members set  status = 0 where username = 'admin'";// and password_hash = '$passwordhash' //Members::STATUS_INACTIVE
		Yii::$app->db->createCommand($sql)->execute();
		
		$sql = "update `companies` set 
				`code` = NULL,
				`code_verified` = 0,
				`code_valid` = 0,
				`status` = 1,
				`serial_no` = NULL,
				`silicon_media_email_sent` = 0,
				`company_name_sent` = NULL
				where 1=1 ";
		Yii::$app->db->createCommand($sql)->execute();
				
		$sql = "select id from `members` where `username` <> 'admin' or username is NULL";		
		$query = Yii::$app->db->createCommand($sql)->queryAll();
		if ($query && is_array($query)  && count($query)){
			foreach ($query as $row) {
				$id = $row['id'];
				$sql = "delete from `student_games` where `studentid`= $id";// and password_hash = '$passwordhash' 
				Yii::$app->db->createCommand($sql)->execute();
			}
		}
				
		$sql = "delete from `members` where `username` <> 'admin' or username is NULL";// and password_hash = '$passwordhash' 
		Yii::$app->db->createCommand($sql)->execute();
		
		//Setup::executeQuery($query);
		return $this->redirect(['index']);
	}	
  	
}