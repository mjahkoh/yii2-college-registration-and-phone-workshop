<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use \yii\web\Response;

use app\models\Jobs;
use app\models\JobsSearch;
use app\models\JobsMembers;
use app\helpers\Setup;
use app\models\SmsSchedule;
use app\models\CreateSms;
use app\models\Payments;
/**
 * JobsController implements the CRUD actions for Jobs model.
 */
class JobsController extends Controller
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
     * Lists all Jobs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JobsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Jobs model.
     * @param integer $id
     * @param string $problem
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Jobs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Jobs();
		$sql = "select company_name, physical_location, mobile_prefix, mobile , tel_prefix , tel from companies limit 1";
		$company = Yii::$app->db->createCommand($sql)->queryOne();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
			$transaction = Yii::$app->db->beginTransaction();
			try {
				
				if (isset($_POST['newclient'])) {	//new client, first save so we get the members id
						//get the 
					//echo "is new client";
					$members = new JobsMembers();
					$members->name = $model->name;
					$members->tel = $model->tel;
					$members->tel_prefix = $model->telprefix;
					$members->category = JobsMembers::STAFF_MEMBER;
					$members->save(false);
					$model->client_id = $members->id;
					
				} else {
					//get the client_id from this (posted data)
					//echo "is old client";
					
				}
				$model->save(false);
				//exit;
				
				//print_r(Yii::$app->request->post());exit;
				$transaction->commit();
				
				//echo $this->context;exit;
				//$view = $this->getView();
				//$message = "test"; $to = "254726434552";
				//$script = "function sendSmsMessage($message, $to) {console.log('a meesage logged in console.'); alert('Hello World');}";
				
				return $this->redirect(['view', 'id' => $model->id]);
			} catch (Exception $e) {
				$transaction->rollback();
				//echo 'errror ';
			}
			return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
			'company' => $company ,
        ]);
    }

    /**
     * Updates an existing Jobs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $problem
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$sql = "select company_name, physical_location, mobile_prefix, mobile , tel_prefix , tel from companies limit 1";
		$company = Yii::$app->db->createCommand($sql)->queryOne();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'problem' => $model->problem]);
        }

        return $this->render('update', [
            'model' => $model,
				'company' => $company ,
        ]);
    }

    /**
     * Deletes an existing Jobs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $problem
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $problem)
    {
        $this->findModel($id, $problem)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Jobs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $problem
     * @return Jobs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Jobs::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
    /**
     *
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
			$model->sendSmsToMultipleMembers($members, $smsmessage);
		} 
		if (Yii::$app->request->isAjax) {
			
			$post = Yii::$app->request->post();
			$member = $_POST['member'];
			//print_r($member);
			//echo "$sql";
			
			if (count($member) == 1) {
				$membersID = $member[0];
			} else {
				$membersID = implode(",", $member);
			}
			//print_r($membersID);
			if (isset($post['multiple']) && $post['multiple'] == true) {
				$sql = "SELECT `jobs_members`.`name` FROM `jobs_members` WHERE id in 
				(SELECT `jobs`.`client_id` FROM `jobs` WHERE id in ($membersID)) 
				 ";// and password_hash = '$passwordhash'
			} else {
				$sql = "SELECT `jobs_members`.`name` FROM `jobs_members` WHERE id in ($membersID) ";// and password_hash = '$passwordhash'
			}
			//echo "$sql";
			//exit;
			$names = Yii::$app->db->createCommand($sql)->queryAll();
			//print_r($names);
			//echo "$sql";exit;
			$comma_separated_names = null;
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
			//return $this->goBack();
			return $this->redirect(['index']);
		}
    }
	
	
	public function actionSetSmsSchedule($to, $message, $status) 
    {
		 if (Yii::$app->request->isAjax) {
			$get = Yii::$app->request->get();
			$message = $get['message'];
			$to = $get['to'];
			$status = $get['status'];
			$sql = "insert into sms_schedule (to, message, status) values ($to, '$message', status) ";
			Yii::$app->db->createCommand($sql)->execute();
		 }
	}	
	
	public function actionGetUnsentSms() 
    {
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$return = false;
		 if (Yii::$app->request->isAjax) {
			$return = Jobs::find()->where(['message_status'=>0])->select('id,message, to')->asArray()->all();
			////print_r($dfdf);
		 }
		 return json_encode($return);
	}	
	
	public function actionSetSmsSentStatus($id, $status = true) //$other
    {
			$sql = "update `jobs` set `message_status` = $status where `id` = $id) ";
			Yii::$app->db->createCommand($sql)->execute();
	}
	
    /**
     * Lists all Jobs models.
     * @return mixed
     */
    public function actionIndexJobsPerClient($member = NULL)
    {
        if ($member == NULL) {
			$sql = "SELECT jobs_members.id FROM jobs_members INNER JOIN jobs ON (jobs_members.id = jobs.client_id) order by name limit 1";
			$members = Yii::$app->db->createCommand($sql)->queryOne();
			if ($members['id']){
				$member = $members['id'];
			} 	
		}
		$searchModel = new JobsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->where("client_id = $member");
		return $this->render('indexJobsPerClient', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'model' => JobsMembers::findOne($member),
		]);
		
    }

    /**
     * Creates a new Jobs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateJobsPerClient($member)
    {
		$model = new Jobs();
		$sql = "select company_name, physical_location, mobile_prefix, mobile , tel_prefix , tel from companies limit 1";
		$company = Yii::$app->db->createCommand($sql)->queryOne();
        if ($model->load(Yii::$app->request->post()) && $model->save() ) { 
				return $this->redirect(['/jobs/view-jobs-per-client', 'id' => $model->id]);
		}
		
		return $this->render('createJobsPerClient', [//Ajax
			'model' => $model,
			'company' => $company ,
			'member' => JobsMembers::findOne($member) ,
		]);
    }

    /**
     * Displays a single Jobs model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewJobsPerClient($id)
    {
		//$sql = "select company_name, physical_location, mobile_prefix, mobile , tel_prefix , tel from companies limit 1";
		//$company = Yii::$app->db->createCommand($sql)->queryOne();

        return $this->render('viewJobsPerClient', [
            'model' => $this->findModel($id),
			//'company' => $company ,
        ]);
    }

    /**
     * Deletes an existing Jobs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteJobsPerClient($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['indexJobsPerClient']);
    }

    /**
     * Updates an existing Jobs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateJobsPerClient($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
					//echo $this->context;/
					//$view = $this->getView();
					////print_r($view); exit;
					//$message = "test"; $to = "254726434552";//
					//$script = "function sendSmsMessage($message, $to) {console.log('a meesage logged in console.'); alert('Hello World');}";
					//$view->registerJs($script, View::POS_END);//, 'my-options'
			return $this->redirect(['view-jobs-per-client', 'id' => $model->id]);
        } else {
				if ($model->hasErrors()) {
					/////print_r($model->getErrors());
				} else {
					///echo 'validation succeeds';
				}			
			return $this->render('updateJobsPerClient', [
                'model' => $model,
				'member' => JobsMembers::findOne($model->client_id) ,
            ]);
        }
    }
	
    public function actionCreateModal($job) 
    {
        $model = new Payments();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->renderAjax('createModal', [
                'model' => $model,
				'job' => $job,
            ]);
        }
    }

}