<?php

namespace app\controllers;

use Yii;
use app\models\payments;
use app\models\PaymentsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl; 
use yii\data\ActiveDataProvider;
use app\models\JobsMembers;
/**
 * PaymentsController implements the CRUD actions for payments model.
 */
class PaymentsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update'],
				'rules' => [
					[
						'allow' => true,
						'roles' => ['@'] ,
					]	
				],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all payments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->setSort(['defaultOrder'=>['date_of_payment'=> SORT_DESC]]);
		/*
		$dataProvider = new ActiveDataProvider([
			'query' => PaymentsSearch::find()
			->orderBy('job_id DESC, date_of_payment DESC'),
			'pagination' => [
				'pageSize' => 20,
			],
		]);*/		

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single payments model.
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
     * Creates a new payments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($job)
    {
        //$request = $this->isAjax();
		///print_r($this->isAjax() ? "yes111": "no11");
		//print_r('jobid:' .$job);
	    $model = new payments();
		if ($model->load(Yii::$app->request->post())) {
			if ($model->save()) { 
				if (Yii::$app->request->isAjax) {
					Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
					Yii::$app->session->setFlash('success', "Payments made successfully.");
					return ['success' => true];
				}
				//return $this->redirect(['index']);
				return $this->goBack();
			} else {
				if (Yii::$app->request->isAjax) {
					Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
					return \yii\widgets\ActiveForm::validate($model);
				}
			}
		} else {
		
			$sql = "select sum(amount) as amount FROM payments WHERE job_id = $job";
			$payments = Yii::$app->db->createCommand($sql)->queryOne();
			$sql = "select charges FROM jobs WHERE id = $job";
			$jobcharges = Yii::$app->db->createCommand($sql)->queryOne();
			$charges = 0;
			if ($jobcharges['charges']) {
				$charges = $jobcharges['charges'];
			}
			//print_r($job);echo "<br>";
			//print_r($payments);echo "<br>";
			//print_r($jobcharges);echo "<br>";
		}
		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('create', [//renderAjax
                'model' => $model,
				'job' => $job,
				'payments' => $payments,
				'jobcharges' => $charges,
            ]);
			
		} else {
			return $this->render('create', ['model' => $model,'job' => $job,]);
		}
				
    }

    /**
     * Updates an existing payments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing payments model.
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
     * Finds the payments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return payments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = payments::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
    protected function isAjax()
    {
		$request = Yii::$app->request;
		//print_r(Yii::$app->request);
		if (!$request->isAjax) {
            throw new BadRequestHttpException('Bad request type', '400');
        }
        return $request;
    }
	
    public function actionPaymentsPerJob($id)
    {
		$searchModel = new PaymentsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->setSort(['defaultOrder' =>['date_of_payment' =>SORT_DESC]]);
		$dataProvider->query->andFilterWhere(['=','job_id',$id]);
		$client = new JobsMembers;
		$sql = "select name from  jobs INNER JOIN jobs_members ON (jobs.client_id = jobs_members.id)
			where jobs.id = $id";
		$jobcharges = Yii::$app->db->createCommand($sql)->queryOne();
		$client = NULL;
		$jobs = Yii::$app->db->createCommand($sql)->queryOne();
		if ($jobs['name']) {
			$client = $jobs['name'];
		}
		return $this->render('paymentsPerJob', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'id' => $id,
			'client' => $client,
			'subtitle' => $this->getPhoneDetails($id),
		]);
    }

    public function getPhoneDetails($jobid)
    {
		$sql = "SELECT charges, SUM(amount) AS amount_paid, (charges - SUM(amount))  AS balance , concat(phone_makes.make, ' ', phone_models.model) as phone
		FROM jobs
		  INNER JOIN payments ON jobs.id = payments.job_id
		  INNER JOIN phone_models ON jobs.phone_model_id = phone_models.id
		  INNER JOIN phone_makes ON phone_models.phone_make_id = phone_makes.id
		  WHERE job_id = $jobid 
		  GROUP BY payments.job_id";
		$subtitle =  NULL;
		$jobcharges = Yii::$app->db->createCommand($sql)->queryOne();
		if ($jobcharges['charges']) {
			$subtitle = "Phone=>  ". $jobcharges['phone'] . ":: Charges=> ". number_format($jobcharges['charges'],0) . " :: Amount Paid=> " .  
			number_format($jobcharges['amount_paid'],0) . " :: Balance=> " .  number_format($jobcharges['balance'],0);
		}
		return $subtitle;
	}	
	
    public function actionCreateNonModalPayment($job)
    {
        $model = new Payments();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['payments-per-job', 'id' => $job]);
        } else {
            return $this->render('createNonModalPayment', [
                'model' => $model,
				'job' => $job,
				'subtitle' => $this->getPhoneDetails($job),
            ]);
        }
    }

}