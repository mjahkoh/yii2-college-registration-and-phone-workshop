<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

use app\models\Countys;
use app\models\Citys;
use app\models\CitysSearch;
/**
 * CitysController implements the CRUD actions for Citys model.
 */
class CitysController extends Controller
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
     * Lists all Citys models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CitysSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Citys model.
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
     * Creates a new Citys model.
"     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		if (Yii::$app->request->queryParams){
			//print_r(Yii::$app->request->queryParams);
			$searchModel = new CitysSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		}
		
        $model = new Citys();
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->city_id]);
        } else {
		    
			return $this->render('create', [
                'model' => $model,
				'dataProviderCitys'  => isset($dataProvider) ? $dataProvider : NULL,
            ]);
        }
    }

    /**
     * Updates an existing Citys model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->city_id]);
        } else {
			return $this->render('update', [
                'model'   => $model,
				'state'   => $model->countys->state_id,
				'county'  => $model->county_id,
            ]);
        }
    }

    /**
     * Deletes an existing Citys model.
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
     * Finds the Citys model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Citys the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Citys::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
    public function actionCityList()//$state, $county
    {
		/*
		$dataProviderCitys = new ActiveDataProvider([
			'query' => Citys::find()
			->joinWith('countys', false, 'INNER JOIN')
			->where(['=','uscitysv_citys.county_id', $county])
			->andWhere(['=','uscitiesv_countys.state_id', $state])
			->orderBy('city'),
			'pagination' => [
				'pageSize' => 20,
			],
		]);
		$model = new Citys();	
		return $this->renderPartial('create', [
			'model'   => $model,
			'state'   => $state,
			'county'  => $county,
			'dataProviderCitys'  => $dataProviderCitys,
		]);
		*/
        $searchModel = new CitysSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		//return  $dataProvider;
    }
	
}