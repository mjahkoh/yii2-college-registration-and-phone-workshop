<?php

namespace app\controllers;

use Yii;
use app\models\Authors;
use app\models\AuthorsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\AuthItem;
use app\models\AuthAssignment;
use yii\filters\AccessControl;

/**
 * AuthorsController implements the CRUD actions for Authors model.
 */
class TestController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'delete', 'view'],
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
     * Lists all Authors models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthorsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Authors model.
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
     * Creates a new Authors model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Authors();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->author_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Authors model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->author_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Authors model.
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
     * Finds the Authors model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Authors the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Authors::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionAdd () 
	{
		$actions = [ "view","update","index","create","delete" ];
        $auth = Yii::$app->authManager;
       
	    $admin = $auth->getRole('admin');
		if ($admin === null) {
			$admin = $auth->createRole('admin');
			$auth->add($admin);
		}
		
		$controllers = ['Authors', 'Book'];
		foreach($controllers as $theaction){
		
			if ($auth->getRole(strtolower("$theaction")) === null) {
				$therole = $auth->createRole(strtolower("$theaction"));
				$auth->add($therole);
				  
				foreach($actions as $controllerAction){
					
					//create permissions
					if ($auth->getPermission("$controllerAction$theaction") === null ) {
						$ControllerPermission = $auth->createPermission("$controllerAction$theaction");
						$ControllerPermission->description = "$controllerAction actions";
						$auth->add($ControllerPermission);
						
						// add "action" role and give this role the "index$actions" permission
						$auth->addChild($therole, $ControllerPermission);	
						
						// add "admin" role and give this role the "index$actions" permission
						// as well as the permissions of the "author" role
						
						$auth->addChild($admin, $ControllerPermission);
					}
					
				}
				
				  
			}
		
		}
		
	}	
	
	public function actionRemove ($id) 
	{
		$auth = Yii::$app->authManager;
		//$actions = ["view","update","index","create","delete"];
		$actions = Yii::$app->params['rbacControllerActions'];
		
		//get admin role
		$adminrole = $auth->getRole("admin") ;
		if ($adminrole !== null) {
			foreach($actions as $controllerAction){
			
				//controller role eg 'Authors'
				$controllerRole = $auth->getRole($id) ;
				$indexControllerPermission = $auth->getPermission("$controllerAction$id");
				//print_r($controllerRole);exit;
				
				$permission = $auth->getPermission("$controllerAction$id");
				if ($permission !== null ) {
					//remove permissions eg indexBranches
					$controllerPermissionRemove = $auth->remove($indexControllerPermission);
					
				}
				if ($controllerRole !== null ) {
					$auth->removeChildren($controllerRole);	
					$controllerRoleRemove = $auth->remove($controllerRole);
				}
			}
			
		}
		
	}
	
}
