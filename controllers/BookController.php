<?php

namespace app\controllers;
use yii\base\Model;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;

use app\models\Book;
use app\models\BookSearch;
/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'delete', 'view', 'index-example-1', 'create-example-1', 'index-example-2', 'index-example-3', 'index-example-4'],
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

    
     /** Lists all Book models.
     * @return mixed
	 */
     
    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	
    /**
     * Displays a single Book model.
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
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Book();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Book model.
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
     * Deletes an existing Book model.
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
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	
     /** Lists all Book models.
     * @return mixed
	 */
     
    public function actionIndexExample1()
    {
		
		$searchModel = new BookSearch();
		if (Yii::$app->request->post()) { 
			/*begin*/
			//$sourceModel = new \namespace\YourGridModel;
			
			$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
			$models = $dataProvider->getModels();			
			//print_r($models);exit;
			$transaction = Yii::$app->db->beginTransaction();
			try {
				if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)) {
					$count = 0;
					foreach ($models as $index => $model) {
						// populate and save records for each model
						if ($model->save()) {
							$count++;
						}
					}
					$transaction->commit();
					Yii::$app->session->setFlash('success', "Processed {$count} records successfully.");
					/*
					return $this->render('indexExample1',[
						'searchModel' => $searchModel,
						'dataProvider' => $dataProvider,
					]);
					*/ // redirect to your next desired page
				} else {
				
				}
				
			} catch (Exception $e) {
				$transaction->rollback();
			}
					/*return $this->render('indexExample1',[
						'searchModel' => $searchModel,
						'dataProvider' => $dataProvider,
					]);*/ 
		} else {
			//$searchModel = new BookSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);	//for activeform
		}
		
		return $this->render('indexExample1', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
		
    }


    public function actionCreateExample1()
    {

        //if ($model->load(Yii::$app->request->post()) && $model->save()) {
		$model = new Book();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            //use ordinary _form
			return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
	

	/*
	action index editable grid*/	
	
	public function actionIndexExample2()
	{
		// your default model and dataProvider generated by gii
		$searchModel = new BookSearch;
		$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
	
		// validate if there is a editable input saved via AJAX
		if (Yii::$app->request->post('hasEditable')) {
			// instantiate your book model for saving
			$bookId = Yii::$app->request->post('editableKey');
			$model = Book::findOne($bookId);
		
			// store a default json response as desired by editable
			$out = Json::encode(['output'=>'', 'message'=>'']);
		
			// fetch the first entry in posted data (there should only be one entry 
			// anyway in this array for an editable submission)
			// - $posted is the posted data for Book without any indexes
			// - $post is the converted array for single model validation
			$posted = current($_POST['Book']);
			$post = ['Book' => $posted];
		
			// load model like any single model validation
			if ($model->load($post)) {
			// can save model or do something before saving model
			$model->save();
		
			// custom output to return to be displayed as the editable grid cell
			// data. Normally this is empty - whereby whatever value is edited by
			// in the input by user is updated automatically.
			$output = '';
		
			// specific use case where you need to validate a specific
			// editable column posted when you have more than one
			// EditableColumn in the grid view. We evaluate here a
			// check to see if buy_amount was posted for the Book model
			if (isset($posted['buy_amount'])) {
			$output = Yii::$app->formatter->asDecimal($model->buy_amount, 2);
			}
		
			// similarly you can check if the name attribute was posted as well
			// if (isset($posted['name'])) {
			// $output = ''; // process as you need
			// }
			$out = Json::encode(['output'=>$output, 'message'=>'']);
			}
			// return ajax json encoded response and exit
			echo $out;
			return;
		}	
		// non-ajax - render the grid by default
		return $this->render('indexExample2', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel
		]);
	}	
	
    
	public function actionIndexExample3()
    {
			$searchModel = new BookSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			return $this->render('indexExample3', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
    }
	/**/
	
	
     /** Lists all Book models.
     * @return mixed
	 */
     
    public function actionIndexExample4()
    {
		$searchModel = new BookSearch();
		
		/*
		$arrayNewDp=
		array(
		array( 'id' => 1, 'name'=> "Lifes Game"			, 'author_id' => 1, 'buy_amount' => 1000.80	, 'sell_amount' => 176.90,     'publish_date' => '01-Dec-2014'),
		array( 'id' => 2, 'name'=> "My Life"			, 'author_id' => 2, 'author_id' => 190.50	, 'sell_amount' => 561.54,    'publish_date' => '02-Jan-2015'),
		array( 'id' => 3, 'name'=> "Criminals Mind"		, 'author_id' => 3, 'author_id' => 715.83	, 'sell_amount' => 145.80,    'publish_date' => '03-May-2016'),
		array( 'id' => 4, 'name'=> "Football a culture"	, 'author_id' => 4, 'author_id' => 156.00	, 'sell_amount' => 169.09,    'publish_date' => '04-Apr-2017'),
		array( 'id' => 5, 'name'=> "Wrestling life"		, 'author_id' => 5, 'author_id' => 56.01	, 'sell_amount' => 134.87,    'publish_date' => '05-Apr-2018'),
		);
		
		$dataProviderArraynewDp = new ArrayDataProvider([
			'allModels'=>$arrayNewDp]);
			*/
	
		if (Yii::$app->request->post()) { 
			
			
			$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
			$models = $dataProvider->getModels();			
			$posts = Yii::$app->request->post('kvTabForm');
			$transaction = Yii::$app->db->beginTransaction();
			try {
				foreach ($posts as $index => $post) {
					// to update an existing book record
					
					echo "id ". $post['id']."<br>";//exit;
					echo "name ". $post['name']."<br>";//exit;
					echo "author_id ". $post['author_id']."<br>";//exit;
					echo "buy_amount ". $post['buy_amount']."<br>";//exit;
					echo "sell_amount ". $post['sell_amount']."<br>";//exit;
					//exit;
					$theBook = Book::findOne($post['id']);
					$theBook->name = $post['name'];
					$theBook->author_id = $post['author_id'];
					$theBook->buy_amount = $post['buy_amount'];
					$theBook->sell_amount = $post['sell_amount'];
					$theBook->publish_date = $post['publish_date'];
					$theBook->save();  // equivalent to $customer->update();				
				}
				$transaction->commit();
			} catch (Exception $e) {
				$transaction->rollback();
			}
			
				
		} else {
			$searchModel = new BookSearch();
		}
		/*data provider using array data provider - non Activeform*/
		$dataProvider = new ArrayDataProvider([
			'allModels'=>[
				['id'=>1, 'name'=> "The River Between"		, 'author_id' => 1, 'buy_amount' => 1000.80	, 'sell_amount' => 176.90,  'publish_date'=>'25-Dec-2014'],
				['id'=>2, 'name'=> "My life in Crime"		, 'author_id' => 2, 'buy_amount' => 190.50	, 'sell_amount' => 561.54,  'publish_date'=>'02-Jan-2014'],
				['id'=>3, 'name'=> "African short stories"	, 'author_id' => 3, 'buy_amount' => 715.83	, 'sell_amount' => 145.80,  'publish_date'=>'11-May-2014'],
				['id'=>4, 'name'=> "Changing the "			, 'author_id' => 4, 'buy_amount' => 156.00	, 'sell_amount' => 169.09,  'publish_date'=>'16-Apr-2014'],
				['id'=>5, 'name'=> "Wrestling life"			, 'author_id' => 5, 'buy_amount' => 56.01	, 'sell_amount' => 134.87,  'publish_date'=>'16-Apr-2014']
			]
		]);
		return $this->render('indexExample4', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			//'dataProviderArraynewDp' => $dataProviderArraynewDp,
		]);
		
    }
	
	
 	 public function actionExample4RefreshGrid()
    {
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			//print_r($_REQUEST);
			//print_r(Yii::$app->request->post());
			//exit();
			$post = Yii::$app->request->post('kvTabForm');
			if (count($post)) { 
				//$post = Yii::$app->request->post('kvTabForm');
				//print_r($post);
				foreach ($post as $item => $value) {
					$arrayNewDp[]=array(
						'id'			=>$value['id'],
						'author_id'		=>$value['author_id'],
						'publish_date'	=>$value['publish_date'],
					);
					
					//$.pjax.reload({container:'#idofyourpjaxwidget'});
//echo  '#'.($item+1). " id: ".$value['id']. " author_id: ".$value['author_id'] . ' publish_date:' .$value['publish_date']. "<br>";
					//echo  "<br>";
					//
				}
				
				$arrayNewDp[]=array(
						'id'			=>NULL,
						'author_id'		=>NULL,
						'publish_date'	=>NULL,
				);
					
					
				$dataProviderArraynewDp = new ArrayDataProvider(['allModels'=>$arrayNewDp]);
				
				//print_r($arrayNewDp);
				
				/**/
				//$dataProvider = new ArrayDataProvider($array);
						/*['id'=>1, 'author_id'=>1, 'publish_date'=>'25-Dec-2014'],
						['id'=>2, 'author_id'=>2, 'publish_date'=>'02-Jan-2014'],
						['id'=>3, 'author_id'=>3, 'publish_date'=>'11-May-2014'],
						['id'=>4, 'author_id'=>4, 'publish_date'=>'16-Apr-2014'],
						['id'=>5, 'author_id'=>5, 'publish_date'=>'16-Apr-2014']
						['id'=>5, 'name'=>'Book Number 5', 'publish_date'=>'16-Apr-2014']*/
			}
			
			echo json_encode(1);
	}	
}


