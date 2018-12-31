<?php

namespace app\controllers;
use yii\base\Model;
use Yii;
use app\models\MarksMaster;
use app\models\Units;
use app\models\MarksDetail;
use app\models\MarksMasterSearch;
use app\models\MarksDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\filters\AccessControl;
use yii\helpers\Html;
/**
 * MarksMasterController implements the CRUD actions for MarksMaster model.
 */
class MarksMasterController extends Controller
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
     * Lists all MarksMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MarksMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MarksMaster model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        
		//print_r(Yii::$app->user->identity) ;exit;
		$searchModel = new MarksDetailSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andFilterWhere(['=', 'marks_master_id', $id]);
		return $this->render('view', [
            'model' => $this->findModel($id),
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new MarksMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
	
		$model = new MarksMaster();
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['update', 'id' => $model->id]);
        } else {
			if ($model->hasErrors()) {
				//print_r($model->getErrors());
				//echo 'dsfsdf failure';
			} else {
				//echo 'validation succeeds';
				//return $this->redirect(['index-charges-per-category', 'id' => $id]);
			}
			return $this->render('create', [
                'model' => $model,
            ]);
        }
		

		return $this->render('create',[
			'model' => $model,
		]); 

    }

    /**
     * Updates an existing MarksMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!$model) {
            throw new NotFoundHttpException("The master model was not found.");
        }		
		//$model->update(false);
        //if ($model->load(Yii::$app->request->post()) && $model->save()) {
		
		$marksdetailsmodels = MarksDetail::find()->where(['marks_master_id'=>$id])->indexBy('id')->all();//
        if (!$marksdetailsmodels) {
            throw new NotFoundHttpException("The details model(s) was not found.");
        }		
		
		if (Yii::$app->request->post()) {
			//print_r(Yii::$app->request->post());exit;
           // print_r( Yii::$app->request->getQueryParams());exit; //exit;
			//print_r( Yii::$app->request->post('MarksMaster'));
			//echo "posted ";exit;
			//$searchModel = new MarksDetailSearch;
			//$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
			//$dataProvider->query->andFilterWhere(['=', 'marks_master_id', $id]);
			//$marksdetailsmodels = $dataProvider->getModels();
			//print_r(Yii::$app->request->post());exit;
			//$searchModel = new MarksDetailSearch();
			//$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
			//$dataProvider->query->andFilterWhere(['=', 'marks_master_id', $id]);
			//$details = $dataProvider->getModels();
			//$marksDetail = new MarksDetail();
			
			
			$items = $this->getItemsToUpdate($id);
			
			if (Model::loadMultiple($items, Yii::$app->request->post()) && 
				Model::validateMultiple($items)) {
				$count = 0;
				foreach ($items as $item) {
				   // populate and save records for each model
					$item->last_date_of_exam = Yii::$app->formatter->asDate($item->last_date_of_exam,'yyyy-MM-dd HH:mm:ss');
					if ($item->save()) {
						// do something here after saving
						$count++;
					}
				}
				Yii::$app->session->setFlash('success', "Processed {$count} records successfully.");
				return $this->redirect(['view', 'id' => $model->id]);
				//echo "sawa";
			} else {
				/////return $this->render('update', [
					/////'items' => $items,   
				/////]);
				//echo "errors";
			}			
			
			//exit;
			
			
        } else {
			$searchModel = new MarksDetailSearch();
			//$detailSearchModel = new MarksDetailSearch();
			//$dataProvider = $detailSearchModel->search(Yii::$app->request->queryParams);	//for activeform
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			$dataProvider->query->andFilterWhere(['=', 'marks_master_id', $id]);
			return $this->render('update', [
                'model' => $model,
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing MarksMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		//MarksDetail::deleteAll('marks_master_id > :marks_master_id', [':marks_master_id' => $id]);
	    $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the MarksMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MarksMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MarksMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
    public function getItemsToUpdate($id)
    {
		$models = MarksDetail::find()->where(['marks_master_id'=>$id])->indexBy('id')->all();
		return $models;

	}	
}