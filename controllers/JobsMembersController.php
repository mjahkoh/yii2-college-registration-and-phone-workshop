<?php

namespace app\controllers;
 
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\filters\AccessControl;
use \yii\web\Response;

use app\models\JobsMembers;
use app\models\JobsMembersSearch;
use app\models\PasswordForm;
use app\models\PasswordResetRequest;
use app\models\AdminPasswordForm;
use app\models\AuthItem;
use app\models\AuthAssignment;
use app\models\CodeForm;
use app\models\CreateSms;
use app\models\CreateBulkSms;
use app\helpers\Setup;
/**
 * MembersController implements the CRUD actions for JobsMembers model.
 */
class JobsMembersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
           /*
		    'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'view', 'update', 'set-admin-password'],
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'] ,
					]	
				],
            ],
			*/
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all JobsMembers models.
     * @return mixed
     */
    public function actionIndex()
    {	
		$model = new JobsMembers();
       // $queryParams = Yii::$app->request->getQueryParams();
		if (Yii::$app->request->post())
		{
			$post = Yii::$app->request->post();
			////print_r($post);
			if (isset($post['memberscat'])) {
				$memberscat = $post['memberscat'];
			} 
			if ($memberscat == JobsMembers::BOTH) {
				$memberscat = (JobsMembers::CLIENTELLE . ',' .  JobsMembers::STAFF_MEMBER);
				//echo "members caaaaaaaaaaaaaaaaaat<br>";
			}
			
				//echo "members cat<br>";
				//print_r($memberscat);
			
		} else {
			////print_r( Yii::$app->request->post());
			//echo "not oooooooooooooooooooooooooooooo postde";
		}
	    //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$dataProvider->query->andFilterWhere(['in','company_payments_details.id',$nonduplicatesids]);
	    //print_r(Yii::$app->request->queryParams);
		$searchModel = new JobsMembersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$dataProvider = $query->andWhere('customers.status'='10');
		////print_r($dataProvider);
		//print_r(Yii::$app->request->queryParams);
		if (Yii::$app->request->queryParams) {
			////print_r(Yii::$app->request->queryParams);
		}
		if (isset($memberscat)) {
			$dataProvider->query->where("category in ($memberscat)");
		}
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
        ]);
    }

    /**
     * Displays a single JobsMembers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new JobsMembers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
		$model = new JobsMembers();
		$authItems = AuthItem::find()->all();
        if ($model->load(Yii::$app->request->post())  ) { 
            ////print_r(Yii::$app->request->post());exit;
			////print_r($model->tel);exit;
			$model->scenario = ( $model->category == JobsMembers::STAFF_MEMBER) ? JobsMembers::SCENARIO_STAFF_MEMBER : JobsMembers::SCENARIO_CLIENTELLE;
			if ($model->validate()) {
				// all inputs are valid
				$model->generateAuthKey();
				$model->save();
				/////$permissionList = $_POST['JobsMembers']['permissions'];
				////print_r($permissionList);//print_r(is_array($permissionList));exit;
				/*
				if (is_array($permissionList) && count($permissionList)) {
					foreach ($permissionList as $value) {
						$newPermission = new AuthAssignment;
						$newPermission->user_id = $model->id;
						$newPermission->item_name = $value;
						$newPermission->save();
					}
				}
				*/
				//send email link
				//
				
				try
				{
					$email = Yii::$app->mailer->compose()
						->setTo($model->email)
						->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
						->setSubject('Signup Confirmation and Activation')
						->setTextBody("
							Click this link to confirm and activate your Account" . Html::a('confirm', Yii::$app->urlManager->createAbsoluteUrl
							(['site/confirm','id'=>$model->id,'key'=>$model->auth_key])
						))
					->send();
					if($email){
						Yii::$app->getSession()->setFlash('success','Check Your confirmation email to Activate your Account !');
					} else {
						Yii::$app->getSession()->setFlash('warning','Failed, contact Admin!');
					}
				}
				catch(\Swift_TransportException $exception)
				{
					//return 'Can sent mail due to the following exception'.//print_r($exception);
				}				
				
				
				////print_r($model->id);exit;
			//
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				// validation failed: $errors is an array containing error messages
				$errors = $model->errors;
			}
			
        } else {
            //$model->scenario = ( $id == JobsMembers::STAFF_MEMBER) ? JobsMembers::SCENARIO_STAFF_MEMBER : JobsMembers::SCENARIO_CLIENTELLE;
			//$id == JobsMembers::STAFF_MEMBER ? [JobsMembers::SCENARIO_STAFF_MEMBER] : [JobsMembers::SCENARIO_CLIENTELLE]
        }
		
		return $this->render('create', [
			'model' => $model,
			'authItems' => $authItems,
			'id' => JobsMembers::BOTH,
		]);
	
    }

    /**
     * Updates an existing JobsMembers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$authItems = AuthItem::find()->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            //$model->scenario = JobsMembers::SCENARIO_UPDATE;
			return $this->render('update', [
                'model' => $model,
				'authItems' => $authItems,
				'id' => JobsMembers::BOTH,
            ]);
        }
    }

    /**
     * Deletes an existing JobsMembers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the JobsMembers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JobsMembers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JobsMembers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	public function actionChangepassword(){
	
		$model = new PasswordForm;
		$modeluser = JobsMembers::find()->where(['username'=>Yii::$app->user->identity->username])->one();
	 
		if($model->load(Yii::$app->request->post())){
			if($model->validate()){
				try{
					
					$modeluser->password = Yii::$app->request->post('PasswordForm')['newpass'];
					if($modeluser->save()){
						Yii::$app->getSession()->setFlash('success','Password changed');
						//return $this->redirect(['index']);
						return $this->goBack();
					} else {
						Yii::$app->getSession()->setFlash('warning','Password not changed');
						return $this->goBack();
						//return $this->redirect(['index']);
					}
				} catch(Exception $e){
					Yii::$app->getSession()->setFlash('warning',"{$e->getMessage()}");
					return $this->render('changepassword',[
						'model'=>$model
					]);
				}
			} else {
				return $this->render('changepassword',[
					'model'=>$model
				]);
			}
		} else {
			return $this->render('changepassword',[
				'model'=>$model
			]);
		}
	}
	
    /**
     * Request reset password
     * @return string
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequest();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('warning', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                'model' => $model,
        ]);
    }

    /**
     * Reset password
     * @return string
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPassword($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                'model' => $model,
        ]);
    }

    /**
     * Activate new user
     * @param integer $id
     * @return type
     * @throws UserException
     * @throws NotFoundHttpException
     */
    public function actionActivate($id)
    {
        /* @var $user User */
        $user = $this->findModel($id);
        if ($user->status == JobsMembers::STATUS_INACTIVE) {
            $user->status = JobsMembers::STATUS_ACTIVE;
            if ($user->save()) {
                return $this->goHome();
            } else {
                $errors = $user->firstErrors;
                throw new UserException(reset($errors));
            }
        }
        return $this->goHome();
    }


	public function actionConfirm($id, $key)
	{
		$user = JobsMembers::find()->where([
			'id' => $id,
			'auth_key' => $key,
			'status' => JobsMembers::STATUS_INACTIVE,
		])->one();
		if(!empty($user)){
			$user->status = JobsMembers::STATUS_ACTIVE;
			$user->save();
			Yii::$app->getSession()->setFlash('success','Success!');
		} else{
			Yii::$app->getSession()->setFlash('warning','Failed!');
		}
		return $this->goHome();
	}
	
	public function actionSetAdminPassword(){
	
		$model = new AdminPasswordForm;
		$modeluser = JobsMembers::find()->where(['username'=> 'admin'])->one();
	 
		if($model->load(Yii::$app->request->post())){
			if($model->validate()){
				try{
					$modeluser->status = JobsMembers::STATUS_ACTIVE;
					$modeluser->password = $modeluser->setPassword(Yii::$app->request->post('AdminPasswordForm')['password']);
					if($modeluser->save()){
						Yii::$app->getSession()->setFlash('success','Password changed');
						//return $this->redirect(['index']);
						Yii::$app->user->logout();
						//return Yii::$app->getResponse()->redirect(array(Url::to(['site/login'],302)));
						//return Yii::$app->getResponse()->redirect('site/login');
						$this->redirect(\Yii::$app->urlManager->createUrl("site/login"));
						//return $this->goHome();						
						/////return $this->goBack();
					} else {
						Yii::$app->getSession()->setFlash('warning','Password not changed');
						return $this->goBack();
						//return $this->redirect(['index']);
					}
				} catch(Exception $e){
					Yii::$app->getSession()->setFlash('warning',"{$e->getMessage()}");
					return $this->render('adminPassword',[
						'model'=>$model
					]);
				}
			} else {
				return $this->render('adminPassword', [
					'model'=>$model
				]);
			}
		} else {
			return $this->render('adminPassword', [
				'model'=>$model
			]);
			
		}
	}

	
	public function actionSetCode(){
	
		$model = new CodeForm;
        if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				//$model->save();
				$sql = "update companies set code = '".$model->code ."' , code_verified= 1";
				Yii::$app->db->createCommand($sql)->execute();
				return $this->goBack();
			} 
		  
           
        } 
		return $this->render('setCode', [
			'model' => $model,
		]);
	}
	
	/*given an ID, search and list the members in that category*/
    public function actionSearchMembers()
    {
		$model = new JobsMembers();
		$get = Yii::$app->request->get();
		$id = $get['id'];
		if (Yii::$app->request->isPjax)
		{
			$model = new JobsMembers(); //reset model
		}
		$model->category = $id;
		/*
		$queryParams = Yii::$app->request->getQueryParams();
		if ($id !== JobsMembers::BOTH) {
			$queryParams['JobsMembersSearch']['category'] = $id;
		} */
		////print_r();
		$searchModel = new JobsMembersSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		if ($id !== JobsMembers::BOTH) {
			$dataProvider->query->andFilterWhere(['=','category',$id]);
		} 
		return $this->renderAjax('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'model' => $model,
		]);   
	}
	
    /**
     * Creates a new Countrys model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateSms()
    {
		////print_r($post);
        $model = new CreateSms();

		if ($model->load(Yii::$app->request->post())) {
			$post = Yii::$app->request->post();
			$members =  $post['CreateSms']['memberssmsid'];
			$smsmessage = $post['CreateSms']['smsmessage'] ;
			$sql = "select concat(tel_prefix,tel) as tel from members where id in ($members)";
			$models = Yii::$app->db->createCommand($sql)->queryAll();
			if (count($models)) {	
				foreach ($models as $index => $model) {
					$to = $model['tel'];
					$themessage[] = [
						'to' => $to, 
						'message' => $smsmessage,
					];
					$sql = "insert  into `sms_messages`(`to`,`message`) values ($to, '$smsmessage')";
					Yii::$app->db->createCommand($sql)->execute();
				}
				//send the messages
				Setup::sendSms($themessage, "Members");
			}
		} 
		if (Yii::$app->request->isAjax) {
			$member = $_POST['member'];
			if (count($member) == 1) {
				$membersID = $member[0];
			} else {
				$membersID = implode(",", $member);
			}
			$sql = "SELECT `members`.`name` FROM `members` WHERE id in ($membersID) ";// and password_hash = '$passwordhash'
			$names = Yii::$app->db->createCommand($sql)->queryAll();
			$comma_separated = null;
			if (count($names)) {
				$result = array_column($names, 'name');
				$comma_separated_names = implode(",", $result);
			}
			$details = [
				'membersID'=> $membersID,
				'names'=> $comma_separated_names,
			];
			return $this->renderAjax('createSms', [
				'model' => $model,
				'members' => $details,
            ]);
			
		} else {
			return $this->goBack();
		}
    }

	
    public function actionCreateBulkSms()
    {
		////print_r($post);
		$model = new CreateBulkSms();
		if ($model->load(Yii::$app->request->post())) {
			$post = Yii::$app->request->post();
			$category = $post['CreateBulkSms']['category'] ;
			$smsmessage = $post['CreateBulkSms']['smsmessage'] ;
			if ($category == JobsMembers::BOTH) {
				$sql = "select concat(tel_prefix,tel) as tel from members";
			} else {
				$sql = "select concat(tel_prefix,tel) as tel from members where category = $category";
			}
			$models = Yii::$app->db->createCommand($sql)->queryAll();
			//echo "$sql";
			//print_r($models);exit;
			if (count($models)) {	
				foreach ($models as $index => $model) {
					$to = $model['tel'];
					$themessage[] = [
						'to' => $to, 
						'message' => $smsmessage,
					];
					$sql = "insert  into `sms_messages`(`to`,`message`) values ($to, '$smsmessage')";
					Yii::$app->db->createCommand($sql)->execute();
				}
				//send the messages
				Setup::sendSms($themessage, "Members");
				//print_r($themessage);
			} 
		} 
        $model = new CreateBulkSms();
		if (Yii::$app->request->isAjax) {

			return $this->renderAjax('createBulkSms', [
				'model' => $model,
				//'members' => $details,
            ]);
			
		} else {
			return $this->render('createBulkSms', [
				'model' => $model,
            ]);
		}
    }

    public function actionIndexStaffMembers()
    {
        $searchModel = new JobsMembersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$dataProvider->query->where("category = ". JobsMembers::STAFF_MEMBER);
		$dataProvider->query->andFilterWhere(['=', 'category',  JobsMembers::STAFF_MEMBER]);
        return $this->render('indexStaffMembers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexClientelle()
    {
        $searchModel = new JobsMembersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$dataProvider->query->where("category = ". JobsMembers::CLIENTELLE);
		$dataProvider->query->andFilterWhere(['=', 'category',  JobsMembers::CLIENTELLE]);
        return $this->render('indexClientelle', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexBoth()
    {
        $searchModel = new JobsMembersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('indexBoth', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
    /**
     * Creates a new JobsMembers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateStaffMembers()
    {
        
		$model = new JobsMembers();
		$authItems = AuthItem::find()->all();
        if ($model->load(Yii::$app->request->post())  ) { 
            ////print_r(Yii::$app->request->post());exit;
			////print_r($model->tel);exit;
			
			if ($model->validate()) {
				// all inputs are valid
				$model->generateAuthKey();
				$model->save();

				//send email link
				echo "to :: ". $model->email ."<br>";
				echo "setFrom :: ". $model->email ."<br>";
				
				try
				{
					$email = Yii::$app->mailer->compose()
						->setTo($model->email)
						->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
						->setSubject('Signup Confirmation and Activation')
						->setTextBody("
							Click this link to confirm and activate your Account" . Html::a('confirm', Yii::$app->urlManager->createAbsoluteUrl
							(['site/confirm','id'=>$model->id,'key'=>$model->auth_key])
						))
					->send();
					if($email){
						Yii::$app->getSession()->setFlash('success','Check Your confirmation email to Activate your Account !');
					} else {
						Yii::$app->getSession()->setFlash('warning','Failed, contact Admin!');
					}
				}
				catch(\Swift_TransportException $exception)
				{
					//return 'Can sent mail due to the following exception'.//print_r($exception);
				}				
				
				
				////print_r($model->id);exit;
			//
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				// validation failed: $errors is an array containing error messages
				$errors = $model->errors;
			}
			
        } else {
            $model->scenario = JobsMembers::SCENARIO_STAFF_MEMBER ;
        }
		
		return $this->render('createStaffMembers', [
			'model' => $model,
			'authItems' => $authItems,
			'id' => JobsMembers::STAFF_MEMBER,
		]);
	
    }



    /**
     * Creates a new JobsMembers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateClientelle()
    {
        
		$model = new JobsMembers();
		$authItems = AuthItem::find()->all();
        if ($model->load(Yii::$app->request->post())  ) { 
            ////print_r(Yii::$app->request->post());exit;
			////print_r($model->tel);exit;
			
			if ($model->validate()) {
				// all inputs are valid
				$model->generateAuthKey();
				$model->save();
				/////$permissionList = $_POST['Members']['permissions'];
				////print_r($permissionList);//print_r(is_array($permissionList));exit;
				/*
				if (is_array($permissionList) && count($permissionList)) {
					foreach ($permissionList as $value) {
						$newPermission = new AuthAssignment;
						$newPermission->user_id = $model->id;
						$newPermission->item_name = $value;
						$newPermission->save();
					}
				}
				*/
				//send email link
				//
				
				try
				{
					$email = Yii::$app->mailer->compose()
						->setTo($model->email)
						->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
						->setSubject('Signup Confirmation and Activation')
						->setTextBody("
							Click this link to confirm and activate your Account" . Html::a('confirm', Yii::$app->urlManager->createAbsoluteUrl
							(['site/confirm','id'=>$model->id,'key'=>$model->auth_key])
						))
					->send();
					if($email){
						Yii::$app->getSession()->setFlash('success','Check Your confirmation email to Activate your Account !');
					} else {
						Yii::$app->getSession()->setFlash('warning','Failed, contact Admin!');
					}
				}
				catch(\Swift_TransportException $exception)
				{
					//return 'Can sent mail due to the following exception'.//print_r($exception);
				}				
				
				
				////print_r($model->id);exit;
			//
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				// validation failed: $errors is an array containing error messages
				$errors = $model->errors;
			}
			
        } else {
            $model->scenario =  JobsMembers::SCENARIO_CLIENTELLE;
        }
		
		return $this->render('createClientelle', [
			'model' => $model,
			'authItems' => $authItems,
			'id' => JobsMembers::CLIENTELLE,
		]);
	
    }



    /**
     * Creates a new JobsMembers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateBoth()
    {
        
		$model = new JobsMembers();
		$authItems = AuthItem::find()->all();
        if ($model->load(Yii::$app->request->post())  ) { 
            ////print_r(Yii::$app->request->post());exit;
			////print_r($model->tel);exit;
			
			if ($model->validate()) {
				// all inputs are valid
				$model->generateAuthKey();
				$model->save();
				/////$permissionList = $_POST['Members']['permissions'];
				////print_r($permissionList);//print_r(is_array($permissionList));exit;
				/*
				if (is_array($permissionList) && count($permissionList)) {
					foreach ($permissionList as $value) {
						$newPermission = new AuthAssignment;
						$newPermission->user_id = $model->id;
						$newPermission->item_name = $value;
						$newPermission->save();
					}
				}
				*/
				//send email link
				//
				
				try
				{
					$email = Yii::$app->mailer->compose()
						->setTo($model->email)
						->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
						->setSubject('Signup Confirmation and Activation')
						->setTextBody("
							Click this link to confirm and activate your Account" . Html::a('confirm', Yii::$app->urlManager->createAbsoluteUrl
							(['site/confirm','id'=>$model->id,'key'=>$model->auth_key])
						))
					->send();
					if($email){
						Yii::$app->getSession()->setFlash('success','Check Your confirmation email to Activate your Account !');
					} else {
						Yii::$app->getSession()->setFlash('warning','Failed, contact Admin!');
					}
				}
				catch(\Swift_TransportException $exception)
				{
					//return 'Can sent mail due to the following exception'.//print_r($exception);
				}				
				
				
				////print_r($model->id);exit;
			//
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				// validation failed: $errors is an array containing error messages
				$errors = $model->errors;
			}
			
        } else {
            $model->scenario = ( $model->category == JobsMembers::STAFF_MEMBER) ? JobsMembers::SCENARIO_STAFF_MEMBER : JobsMembers::SCENARIO_CLIENTELLE;
			//$id == JobsMembers::STAFF_MEMBER ? [JobsMembers::SCENARIO_STAFF_MEMBER] : [JobsMembers::SCENARIO_CLIENTELLE]
        }
		
		return $this->render('createBoth', [
			'model' => $model,
			'authItems' => $authItems,
			'id' => JobsMembers::BOTH,
		]);
	
    }
	
    /**
     * Updates an existing JobsMembers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateStaffMembers($id)
    {
        $model = $this->findModel($id);
		$authItems = AuthItem::find()->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view-staff-members', 'id' => $model->id]);
        } else {
            //$model->scenario = JobsMembers::SCENARIO_UPDATE;
			return $this->render('updateStaffMembers', [
                'model' => $model,
				'authItems' => $authItems,
				'id' => JobsMembers::STAFF_MEMBER,
            ]);
        }
    }

    /**
     * Updates an existing JobsMembers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateClientelle($id)
    {
        $model = $this->findModel($id);
		$authItems = AuthItem::find()->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view-clientelle', 'id' => $model->id]);
        } else {
            //$model->scenario = JobsMembers::SCENARIO_UPDATE;
			return $this->render('updateClientelle', [
                'model' => $model,
				'authItems' => $authItems,
				'id' => JobsMembers::CLIENTELLE,
            ]);
        }
    }


    /**
     * Updates an existing JobsMembers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateBoth($id)
    {
        $model = $this->findModel($id);
		$authItems = AuthItem::find()->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view-both', 'id' => $model->id]);
        } else {
            //$model->scenario = JobsMembers::SCENARIO_UPDATE;
			return $this->render('updateBoth', [
                'model' => $model,
				'authItems' => $authItems,
				'id' => JobsMembers::BOTH,
            ]);
        }
    }

    public function actionViewStaffMembers($id)
    {
        return $this->render('viewStaffMembers', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionViewClientelle($id)
    {
        return $this->render('viewClientelle', [
            'model' => $this->findModel($id),
        ]);
    }



    public function actionViewBoth($id)
    {
        return $this->render('viewBoth', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDeleteStaffMembers($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index-staff-members']);
    }
	
    public function actionDeleteClientelle($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index-clientelle']);
    }
	
    public function actionDeleteBoth($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index-both']);
    }
	
}
