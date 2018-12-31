<?php

namespace app\controllers;

use Yii;
use app\models\Branches;
use app\models\Cookies;
use app\models\BranchesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use \yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
/**
 * BranchesController implements the CRUD actions for Branches model.
 */
class BranchesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'delete', 'view', 'set-cookie'],
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
     * Lists all Branches models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BranchesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Branches model.
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
     * Creates a new Branches model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() 
    {
		/////if (Yii::$app->user->can('create-branch')) {
			$model = new Branches();
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
					return $this->redirect(['index.php/branches/view', 'id' => $model->id]);
					//$model->date_created = date ('Y-m-d h:m:s');
			} else {
				return $this->render('create', [
					'model' => $model,
				]);
			}
		/////} else {
			/////throw new ForbiddenHttpException;
		/////}
    }

    /**
     * Updates an existing Branches model.
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
     * Deletes an existing Branches model.
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
     * Finds the Branches model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Branches the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Branches::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	    /**
     * Lists all Branches models.
     * @return mixed
     */
    public function actionIndexModal()
    {
        $searchModel = new BranchesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('indexModal', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
    /**
     * Creates a new Branches model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateModal() 
    {
        $model = new Branches();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->renderAjax('createModal', [
                'model' => $model,
            ]);
        }
    }
	
	/**
     * Lists all Branches models.
     * @return mixed
     */
    public function actionIndexModalAjax()
    {
        $searchModel = new BranchesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('indexModalAjax', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	/**
     * Lists all Branches models.
     * @return mixed
     */
    public function actionIndexModalAjax2()
    {
        $searchModel = new BranchesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('indexModalAjax2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	
	
    /**
     * Creates a new Branches model using ajax.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateModalAjax()
    {
        //$request = $this->isAjax();
		///print_r($this->isAjax() ? "yes111": "no11");
		//print_r('jobid:' .$job);
	    $model = new Branches();
		if ($model->load(Yii::$app->request->post())) {
			
			if ($model->save()) { 
				if (Yii::$app->request->isAjax) {
					Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
					return ['success' => true];
				} 
 				return $this->goBack();
			} else {
				if (Yii::$app->request->isAjax) {
					Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
					return \yii\widgets\ActiveForm::validate($model);
				}
			}
		} 
		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('createModalAjax', [//renderAjax
                'model' => $model,
            ]);
			
		} else {
			return $this->render('createModalAjax', ['model' => $model]);
		}
				
    }
	
	public function actionValidate()
	{
		$model = new Branches();
		$request = \Yii::$app->getRequest();
		if ($request->isPost && $model->load($request->post())) {
			\Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
	}
	
	/**/
	public function actionSave()
	{
		$model = new Branches();
		$request = \Yii::$app->getRequest();
		if ($request->isPost && $model->load($request->post())) {
			\Yii::$app->response->format = Response::FORMAT_JSON;
			return ['success' => $model->save()];
		} 
		return $this->renderAjax('createModalAjax2', [
			'model' => $model,
		]);
	}
	
	
    /**
     * Creates a new Branches model using ajax.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateModalAjax2()
    {
        //$request = $this->isAjax();
		///print_r($this->isAjax() ? "yes111": "no11");
		//print_r('jobid:' .$job);
	    $model = new Branches();
		if ($model->load(Yii::$app->request->post())) {
			
			if ($model->save()) { 
				if (Yii::$app->request->isAjax) {
					Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
					return ['success' => true];
				} 
 				return $this->goBack();
			} else {
				if (Yii::$app->request->isAjax) {
					Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
					return \yii\widgets\ActiveForm::validate($model);
				}
			}
		} 
		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('createModalAjax2', [//renderAjax
                'model' => $model,
            ]);
			
		} else {
			return $this->render('createModal2', ['model' => $model]);
		}
				
    }
	
    /**
     * Request reset password
     * @return string
     */
    public function actionSetCookie()
    {
        $model = new Cookies();
		
        if (Yii::$app->getRequest()->post()) {
            $request = Yii::$app->request->post('Cookies');
			$cookies = Yii::$app->response->cookies;
			$cookies = new\yii\web\cookie([
				'name' =>  $request['cookie_name'],
				'value' => $request['value'],
			]);
			//print_r($request['cookie_name']);echo "<br>";
			//add cookie to browser 
			//$result = Yii::$app->getResponse()->getCookies()->add($cookies);
			//print_r($cookies);
			//echo "<br>";
			
			$stored_cookies = Yii::$app->request->cookies;
			//get the cookie value. if not set, enter default value
			$name = $stored_cookies->getValue($request['cookie_name'], 'default_value');
			//return default value if the cookie is not available
			
			//print_r($name);
			//echo "<br>";
			//if cookie is set, send it to flash
			if ($stored_cookies->has($request['cookie_name'])) {
                Yii::$app->getSession()->setFlash('success', 'Your Cookie name is: ' . $request['cookie_name']. " and the value is ".$request['value']);
               // return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('warning', 'Sorry, we are unable to set the Cookie.');
            }
        }

        return $this->render('cookies', [
                'model' => $model,
        ]);
    }

	
	
}