<?php
 
namespace app\controllers;
use Yii;
//use yii\console\Controller;
//namespace app\commands; 
//Yii::$app->user->isGuest
//Yii::$app->user->identity->username === 'admin'
 
use yii\web\Controller;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\ControllerRole;  

use app\models\AuthItem;
use app\models\Members;
use app\models\MembersSearch;
//use app\models\Members;
use app\models\AuthAssignment;
use app\helpers\Setup;  
  
class RbacController extends Controller 
{
	
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'init', 'controller-roles', 'get-roles', 'create-controller-role', 'index', 'get-permissions', 'index-members', 
					'rights-allocation'
				],
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
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */ 
    public function actionCreate()
    {
        /**/
		if (!Yii::$app->user->isGuest &&  Yii::$app->user->identity->isAdmin ) {
			Yii::$app->getResponse()->format = 'json';
			$routes = Yii::$app->getRequest()->post('route', '');
			$routes = preg_split('/\s*,\s*/', trim($routes), -1, PREG_SPLIT_NO_EMPTY);
			$model = new Route();
			$model->addNew($routes);
			return $model->getRoutes();
		} else {
			 Yii::$app->session->setFlash('warning', 'Only Admins are allowed access this page');
			 return $this->goBack();
		}
    }
 
	protected function createControllerRole($controllerItem )
	{
	
		$auth = Yii::$app->authManager;
		
		//get admin role if available or create one
		//print_r(Yii::$app->authManager->getRole('admins') !== null) ;
		
		if (Yii::$app->authManager->getRole('admins') === null) {
			$admin = $auth->createRole('admin');
		}
		
		//print_r($controllerItem);exit;
		$obj = [];
		/*
		$i=0;
			if ($this->actionExists(strtolower($controllerItem), "index")) {
				$obj[0] = 'index';
			} 
		if ($this->actionExists(strtolower($controllerItem), "view")) {
			$obj[1] = 'view';
		}
		if ($this->actionExists(strtolower($controllerItem), "create")) {
			$obj[2] = 'create';
		}
		if ($this->actionExists(strtolower($controllerItem), "update")) {
			$obj[3] = 'update';
		}
		if ($this->actionExists(strtolower($controllerItem), "delete")) {
			$obj[4] = 'delete';
		}
		*/
		
		//new
		$allControllerActions = Yii::$app->params['rbacControllerActions'];
		$i=0;
		foreach($allControllerActions as $controllerAction){
			if ($this->actionExists(strtolower($controllerItem), $controllerAction)) {
				$obj[$i] = $controllerAction;
				$i++;
			} 
		}
		//end new
		
		//print_r($obj);
		if (count($obj)) {
			$controller[$controllerItem."Controller"] = $obj;
			return $this->createControllerRoles($controller, true);
		}
		return false;
		 
	}

	public function actionInit ($remove = []) 
	{

		if (Yii::$app->user->identity->username === 'admin' ) {
		
			if (Yii::$app->request->post()) {
				$auth = Yii::$app->authManager;
				$auth->removeAll();	
				
				$allControllerActions = Yii::$app->params['rbacControllerActions'];
						
				$allactions = $this->getAllControllerActions($remove, true);
				if (Yii::$app->authManager->getRole(strtolower("admin")) === null) {
					$admin = $auth->createRole('admin');
					$auth->add($admin);
				} else {
					$admin  = $auth->getRole('admin');
				}
				
				if ($this->createControllerRoles($allactions, $admin) > 0) {
					Yii::$app->session->setFlash('success', "Rights were Initialised properly.");
				} else {
					Yii::$app->session->setFlash('warning', "Rights were not Initialised.");
				}
				return $this->redirect(['index-members']);
			
			} 
			return $this->render('initialiseRights', [
				//'model' => $model,
			]);
		} else {
			 Yii::$app->session->setFlash('warning', 'Only Admins are allowed access this page');
			 return $this->goBack();
		}

		
	}

	//make the controller roles ie index/view/delete/update
	public function createControllerRoles ($allactions, $admin) {
	
		$auth = Yii::$app->authManager;
		$counter = 0;
		$admin = $auth->getRole("admin");
		
		//print_r($admin);exit;
		/*
		for all actions create a permission - index, view/create/update/delete
		in the form createPost, updatePost
		*/
		foreach ($allactions as $actions => $action) {
			   // $theaction = 'index', 'create' , 'update', 'delete', 'view'
			  //remove the word controller from the action
			  echo "actions: $actions<br>";
			  $theaction = substr_replace($actions, '', -10, 10);
			  /////echo "theaction: $theaction<br>";exit;
			  //add roles to $theaction eg author
			  
			  //first check wether  $theaction has been created
			  if (Yii::$app->authManager->getRole(strtolower("$theaction")) === null) {
				  $therole = $auth->createRole(strtolower("$theaction"));
				  $auth->add($therole);

				  /*	
				  //index 
				  if ( in_array('index', $action)) {
						
						//creete permissions
						$indexController = $auth->createPermission("index$theaction");
						$indexController->description = "Index $actions";
						$auth->add($indexController);
						
						// add "action" role and give this role the "index$actions" permission
						$auth->addChild($therole, $indexController);	
						
						// add "admin" role and give this role the "index$actions" permission
						// as well as the permissions of the "author" role
						
						$auth->addChild($admin, $indexController);
						$counter++;
				  } 
				   
				  // view
				  if ( in_array('view', $action)) {
						
						//creete permissions
						$viewController = $auth->createPermission("view$theaction");
						$viewController->description = "View $actions";
						$auth->add($viewController);
						
						// add "action" role and give this role the "index$actions" permission
						$auth->addChild($therole, $viewController);	
						
						// add "admin" role and give this role the "index$actions" permission
						// as well as the permissions of the "author" role
						$auth->addChild($admin, $viewController);
						$counter++;
				  } 
		
		
				  // update
				  if ( in_array('update', $action)) {
						
						//creete permissions
						$updateController = $auth->createPermission("update$theaction");
						$updateController->description = "Update $actions";
						$auth->add($updateController);
						
						// add "action" role and give this role the "index$actions" permission
						$auth->addChild($therole, $updateController);	
						
						// add "admin" role and give this role the "index$actions" permission
						// as well as the permissions of the "author" role
						$auth->addChild($admin, $updateController);
						$counter++;
				  } 
		
		
		
				  //delete 
				  if ( in_array('delete', $action)) {
						
						//creete permissions
						$deleteController = $auth->createPermission("delete$theaction");
						$deleteController->description = "Delete $actions";
						$auth->add($deleteController);
						
						// add "action" role and give this role the "index$actions" permission
						$auth->addChild($therole, $deleteController);	
						
						// add "admin" role and give this role the "index$actions" permission
						// as well as the permissions of the "author" role
						$auth->addChild($admin, $deleteController);
						$counter++;
				  } 
				  
				  //create*
				  if ( in_array('create', $action)) {
						
						//creete permissions
						$createController = $auth->createPermission("create$theaction");
						$createController->description = "create $actions";
						$auth->add($createController);
						
						// add "action" role and give this role the "create$actions" permission
						$auth->addChild($therole, $createController);	
						
						// add "admin" role and give this role the "create$actions" permission
						// as well as the permissions of the "author" role
						$auth->addChild($admin, $createController);
						$counter++;
				  } 
				  */
				  
				  //new
				  $allControllerActions = Yii::$app->params['rbacControllerActions'];
				  foreach($allControllerActions as $controllerAction){
				  	
					if (in_array($controllerAction, $action)) {
						//creete permissions
						$ControllerActionName = $controllerAction."Controller";	//eg indexController
						
						$ControllerActionName = $auth->createPermission("$controllerAction$theaction");
						$ControllerActionName->description = "$controllerAction $actions";
						$auth->add($ControllerActionName);
						
						// add "action" role and give this role the "create$actions" permission
						$auth->addChild($therole, $ControllerActionName);	
						
						// add "admin" role and give this role the "create$actions" permission
						// as well as the permissions of the "author" role
						$auth->addChild($admin, $ControllerActionName);
						$counter++;
					}
				  
				  }
				  //end new
				  
				  
			  }


		}
		return ($counter > 0) ? true : false;	  
	
	}
					

	public function actionGetRoles () 
	{
	
		//$auth = Yii::$app->authManager;
		$roles = Yii::$app->authManager->getRoles();
		/////foreach($roles as $key => $role){
			/////echo 'name : ' . ucfirst($role->name) . ' :: description : ' . $role->description . "<br />";
		/////}
		//echo "<br>";
		return $roles;
		// print_r(Yii::$app->authManager->getRole("deletedfsdJobs"));
		
  	}

	//retrives all the controllers and their actions. 
	// it only includes actions index/create/update/delete/view
	/*
	$params 
		- remove - the controllers to be removed from the final list
		- truncate - removes the controller part from the Action eg AuthorsController=>Authors
	*/
	protected function getAllControllerActions($remove, $truncate = false)
	{
		$removelist = ($remove == NULL) ? [] : $remove;
		$requiredactions = Yii::$app->params['rbacControllerActions'];
		$controllerlist = [];
		if ($handle = opendir('../controllers')) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php' ) {
					if (count($remove)) {
						if (!in_array($file, $remove)) {
							$controllerlist[] = $file;
						} 
					} else {
						$controllerlist[] = $file;
					}
						
				}
			}
			closedir($handle);
		}
		asort($controllerlist);
		$fulllist = [];
		foreach ($controllerlist as $controller):
			$handle = fopen('../controllers/' . $controller, "r");
			if ($handle) {
				while (($line = fgets($handle)) !== false) {
					if (preg_match('/public function action(.*?)\(/', $line, $display)):
						if (strlen($display[1]) > 2):
							$action = strtolower($display[1]);
							//echo $action."<br>";
							if (in_array($action, $requiredactions, true)) {
								//echo "its in: $action<br>";
								$fulllist[substr($controller, 0, -4)][] = $action;
							} 
						endif;
					endif;
				}
			}
			fclose($handle);
		endforeach;
		return $fulllist;
	}
	
	
    public function actionCreateControllerRole()
    {

		$model = new ControllerRole();
        if ($model->load(Yii::$app->request->post()) ) {
			if ($model->validate() ) {
				$post = Yii::$app->request->post();
				$controllerName =  $post['ControllerRole']['controllerName'];
				$return = $this->createControllerRole($controllerName);
				if ($return) {
					Yii::$app->getSession()->setFlash('success', 'Your Controller Roles has been set: ');
				} else {
					Yii::$app->getSession()->setFlash('warning', 'Sorry, we are unable to set the Controller Roles.');
				}
				return $this->redirect(['create-controller-role']);
			} else {
				Yii::$app->getSession()->setFlash('warning', 'Sorry, we are unable to set the Controller Roles.');
			}
        } 
		return $this->render('addcontrollerrole', [
			'model' => $model,
		]);
        
	
	}
	
    public function actionIndex()
    {
		if (Yii::$app->user->identity->username === 'admin' ) {
		
			$query = AuthItem::find()->where(['type'=>1])->orderBy('name');
			$dataProvider = new ActiveDataProvider([
				 'query' => $query,
			]);
	
			return $this->render('index', [
				'dataProvider' => $dataProvider,
			]);
		} else {
			 Yii::$app->session->setFlash('warning', 'Only Admins are allowed access this page');
			 return $this->goBack();
		}
    }
	
	
	public function actionGetPermissions () {

		$permissions = Yii::$app->authManager->getPermissions();
		return $permissions;
	}
	
	//lists all members
	public function actionIndexMembers () {
		$searchModel = new MembersSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	
		return $this->render('indexMembers', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
		
	}
	
	public function flattenArray  ($object) {
		//print_r( $object);exit;
		
		/*
		$arr['create'] = NULL;
		$arr['delete'] = NULL;
		$arr['view'] = NULL;
		$arr['index'] = NULL;
		$arr['update'] = NULL;
		*/
		$allControllerActions = Yii::$app->params['rbacControllerActions'];
		foreach($allControllerActions as $controllerAction){
			$arr[$controllerAction] = NULL;
		}
		
		foreach ($object as $obj){
			//print_r($obj->name);
			//echo "<br>";
			  //$name = $obj->name;
			  //lets remove the rolename and remain with the action eg createAuthors => create	 
			  /*
			  if (substr($obj->name, 0,6) == "create") {
			  	$arr['create'] =  $obj->name;
			  }		
			  if (substr($obj->name, 0,5) == "index") {
			  	$arr['index'] =  $obj->name;
			  }		 
			  if (substr($obj->name, 0,6) == "delete") {
			  	$arr['delete'] =  $obj->name;
			  }		 
			  if (substr($obj->name, 0,4) == "view") {
			  	$arr['view'] = $obj->name;
			  }		 
			  if (substr($obj->name, 0,6) == "update") {
			  	$arr['update'] = $obj->name;
			  }		 
			  */
			  
			  //start new
			  foreach($allControllerActions as $controllerAction){
			  	//if substr(createAuthors, 0, strlen($controllerAction)) == $controllerAction)
				if (substr($obj->name, 0, strlen($controllerAction)) == $controllerAction) {
					$arr[$controllerAction] = $obj->name;
				}
			  
			  }	
			  
			  //end new
			  
			  	
		}		
		//print_r($arr);
		return $arr;
	}
	
	//given a members id , get the users permissions 
	public function actionRightsAllocation ($id) {

		if (Yii::$app->user->identity->username === 'admin' ) {
		
			if (isset($_REQUEST['selection'])) {
				//print_r($_REQUEST);
				//print_r($_REQUEST['selection']);exit;
				//print_r($_REQUEST['selection']);
				
				//revoke all permissions to the user
				Yii::$app->authManager->revokeAll($id);
				
				$permissionList = $_REQUEST['selection'];
				foreach($permissionList as $value){
					$newpermission = new AuthAssignment;
					$newpermission->user_id = $id;
					$newpermission->item_name = $value;
					$newpermission->save();
				}
				
				
			}//exit;
			
			$roles = $this->actionGetRoles();
			//$data = order::getsome()->asArray();
			//print_r($data);exit;
			$i=1;
			foreach($roles as $key => $role){
				//echo 'name : ' . ucfirst($role->name) . ' :: description : ' . $role->description . "<br />";
				$omittedControllers = Yii::$app->params['rbacControllerActions'];
				if (!in_array($role->name, $omittedControllers)) {
				//if ($role->name !== "admin" && $role->name !== "rbac"  && $role->name !== "site") {
					//echo "<br>role: ".$role->name."<br>";
					//print_r(Yii::$app->authManager->getPermissionsByRole($role->name));
					$object = Yii::$app->authManager->getPermissionsByRole($role->name);
					//print_r($object);exit;
					$array = $this->flattenArray($object);
					//print_r($array);exit;
					//echo "<br>";
					$common = [];
					$initial = [
						'id'			=> $i,
						'role'			=> $role->name,
						'permission'	=> 0,
						//'index'			=> $array['index'],
						//'update'		=> $array['update'],
						//'view'			=> $array['view'],
						//'delete'		=> $array['delete'],
						//'create'		=> $array['create'],
					];
					
					//start
					$allControllerActions = Yii::$app->params['rbacControllerActions'];
					foreach($allControllerActions as $controllerAction){
						$common[$controllerAction] = $array[$controllerAction];
					}
					
					$arraydata[] = array_merge($initial, $common);
					
					//end
					
					
					$i++;
					
				}
			}
			//print_r($arraydata);
			//exit;
			
			$dataProvider = new ArrayDataProvider([
				'key'=> 'id',
				'allModels'=> $arraydata,
				'sort'=> [
					//'attributes'=> ['id', 'paid', 'vendorid', 'ordeid'],
				],
				'pagination' => [
					'pageSize' => false,
				],
				]);
			$model = Members::findone($id);
			return $this->render('rightsAllocation', [
				//'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'id' => $id,
				'model' => $model,
			]);
			
		} else {
			 Yii::$app->session->setFlash('warning', 'Only Admins are allowed access this page');
			 return $this->goBack();
		}

	}
	
	
	public function actionExists($controllerId, $actionId, $module = null)
	{
		if ($module === null) {
			$module = Yii::$app;
		}
		//print_r($controllerId);
		$controller = $module->createControllerByID($controllerId);
		//print_r($controller);exit;
		if ($controller === null) {
			return false;
		}
		$action = $controller->createAction($actionId);
		if ($action === null) {
			return false;
		}
		return true;
	}
	
	//removes a controller role given an id
	public function actionRemove ($id) 
	{
	
		if (Yii::$app->user->identity->username === 'admin' ) {
		
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
				Yii::$app->session->setFlash('success', "$id Controller was removed.");
				
			}
			return $this->redirect(['index']);
			
		} else {
			 Yii::$app->session->setFlash('warning', 'Only Admins are allowed access this page');
			 return $this->goBack();
		}
		
	}
	
	
}