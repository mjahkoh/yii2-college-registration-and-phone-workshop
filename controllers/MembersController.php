<?php

namespace app\controllers;
use yii\data\SqlDataProvider;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\AuthItem;
use app\models\AuthAssignment;
use app\models\PasswordForm;
use app\models\PasswordResetRequest;
use app\models\AdminPasswordForm;

use app\models\CodeForm;
use app\models\Members;
use app\models\Games;
use app\models\Citys;
use app\models\StudentGames;
use app\models\MembersSearch;
/**
 * MembersController implements the CRUD actions for Members model.
 */
class MembersController extends Controller
{
    
	/**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Members models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $searchModel = new MembersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['category'=> $id]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'id' => $id,
        ]);
    }

    /**
     * Displays a single Members model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$dataProvider = new ActiveDataProvider([
			'query' => StudentGames::find()
			->where(['studentid' => $id])
			->orderBy('gamesid'),
			'pagination' => [
				'pageSize' => 20,
			],
		]);
        return $this->render('view', [
            'model' => $this->findModel($id),
			'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Members model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
	
	    $authItems = AuthItem::find()->all();
		//$cat =  Yii::$app->request->get('cat');
		$model = new Members($id == Members::STUDENT ?
			['scenario'=>Members::SCENARIO_STUDENTS] : ['scenario'=>Members::SCENARIO_PERSONNEL]);
	
        //$model = new Members();
		
		if (Yii::$app->request->isAjax && $model->load($_POST)) {
			Yii::$app->response->format = 'json';
			return \yii\widgets\Activeform::validate($model);
		}
		
		$errors = false;
		
		if (Yii::$app->request->post()) {
			
			$keys = Yii::$app->request->post('selection');
			$transaction = Yii::$app->db->beginTransaction();
			
			try {
			
				if (!$model->load(Yii::$app->request->post())) {
					$errors = true;
				}
			
				$model->setPassword($model->password);
				$model->generateAuthKey();
				
				if (!$model->save()) {
					$errors = true;
				}
				
				if ($errors === true) {
					//print_r($model->getErrors());
					$dataProvider = new ActiveDataProvider([
						'query' => Games::find()
						->orderBy('game'),
						'pagination' => [
							'pageSize' => 20,
						],
					]);
					return $this->render('create', [
						'model' => $model,
						'dataProvider' => $dataProvider,
						'authItems' => $authItems,
						'id' => $id,
					]);
				}
				
				if ($keys && is_array($keys)  && count($keys)){
				
					foreach ($keys as $value) {
						// INSERT (table name, column values)
						Yii::$app->db->createCommand()->insert('student_games', [
							'gamesid' => $value,
							'studentid' =>  $model->id,
						])
						->execute();
					}			
				}
					
					$transaction->commit();
					//return $this->redirect(['view', 'id' => $model->id]);
					
			} catch (Exception $e) {
				$transaction->rollback();
				
			}
		
			
			/*$info = RbacHelper::assignRole($model->getId());
			if (!$info) {
				Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while saving user role.'));
			}*/
			
			return $this->redirect(['view', 'id' => $model->id]);
			
		//}
		
        //if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
			
        } else {
            
			$dataProvider = new ActiveDataProvider([
				'query' => Games::find()
				->orderBy('game'),
				'pagination' => [
					'pageSize' => 20,
				],
			]);

			return $this->render('create', [
                'model' => $model,
				'dataProvider' => $dataProvider,
				'authItems' => $authItems,
				'id' => $id,
				//'modelGames' => $modelGames,
				//'games' => $games,
            ]);
        }
		
		/*model wasnt saved*/
    }

    /**
     * Updates an existing Members model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		if (Yii::$app->request->post()) {
			$keys = Yii::$app->request->post('selection');

			$transaction = Yii::$app->db->beginTransaction();
			try {
				if ($model->load(Yii::$app->request->post()) && $model->save()) {
					
					//delete from student_games where studentid = $model->id
					//Members::deleteAll('age > :age AND gender = :gender', [':age' => 20, ':gender' => 'M']);
					StudentGames::deleteAll('studentid = :studentid ', [':studentid' => $model->id]);
					
					if ($keys && is_array($keys)  && count($keys)){
					
						foreach ($keys as $value) {
							// INSERT (table name, column values)
							Yii::$app->db->createCommand()->insert('student_games', [
								'gamesid' => $value,
								'studentid' =>  $model->id,
							])
							->execute();
						}			
					}
					
					$transaction->commit();
					return $this->redirect(['view', 'id' => $model->id]);
				} else {
					if ($model->hasErrors()) {
						print_r($model->getErrors());
					}
					echo "sadasd";exit;
				}
			} catch (Exception $e) {
				$transaction->rollback();
			}
		
			return $this->redirect(['view', 'id' => $model->id]);
			
        } else {
            
			$count = Yii::$app->db->createCommand("
				SELECT COUNT(*) FROM student_games   right JOIN games ON (student_games.gamesid=games.id) WHERE student_games.studentid=:id", [':id' => $id])->queryScalar();			
			//echo "count: $count<br>";exit;
			$dataProvider = new SqlDataProvider([
				'sql' => "SELECT games.*, if ((select gamesid from  student_games where student_games.gamesid=games.id and student_games.studentid=:id),1,0) as gamesid FROM games order by games.game",	
				'params' => [':id' => $id],
				'totalCount' => $count,
				'key'        => 'gamesid',  //
				'sort' => [
					'attributes' => [
						'games.game',
						'games.id',
						'student_games.gamesid',
					],
				],
				'pagination' => [
					'pageSize' => 20,
				],
			]);			

			$model->scenario = Members::SCENARIO_UPDATE;
			//$authItems = AuthItem::find()->all();
			return $this->render('update', [
                'model' => $model,
				'dataProvider' => $dataProvider,
				//'authItems' => $authItems,
            ]);
        }
    }

    /**
     * Deletes an existing Members model.
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
     * Finds the Members model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Members the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Members::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionChangepassword(){
	
		$model = new PasswordForm;
		$modeluser = Members::find()->where(['username'=>Yii::$app->user->identity->username])->one();
	 
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
        if ($user->status == Members::STATUS_INACTIVE) {
            $user->status = Members::STATUS_ACTIVE;
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
		$user = Members::find()->where([
			'id' => $id,
			'auth_key' => $key,
			'status' => Members::STATUS_INACTIVE,
		])->one();
		if(!empty($user)){
			$user->status = Members::STATUS_ACTIVE;
			$user->save();
			Yii::$app->getSession()->setFlash('success','Success!');
		} else{
			Yii::$app->getSession()->setFlash('warning','Failed!');
		}
		return $this->goHome();
	}
	
	public function actionSetAdminPassword(){
	
		
		if (Members::isAdminInitialised() == false ) {
			$modeluser = Members::find()->where(['username'=> 'admin'])->one();
			$model = new AdminPasswordForm;
			if($model->load(Yii::$app->request->post())){
				if($model->validate()){
					try{
						$modeluser->status = Members::STATUS_ACTIVE;
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
			
		} else {
			Yii::$app->getSession()->setFlash('warning',"The Admin Password is already initialised.  Please request for a new password  from 'Reset Password SubMenu'");
			return $this->goBack();
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
	
	public function actionAllocateRights(){
		/*
		$model = Members::find()->orderBy('firstname ASC, middlename ASC, surname ASC')->all();
		return $this->render('allocate-rights', [
			'model' => $model,
		]);*/
		
        $searchModel = new MembersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('allocate-rights', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}	
	
}