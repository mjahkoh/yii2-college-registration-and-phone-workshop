<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use app\models\PhoneMakes;
use app\models\PhoneModels;
use app\models\PhoneModelsSearch;

/**
 * PhoneModelsController implements the CRUD actions for PhoneModels model.
 */
class PhoneModelsController extends Controller
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
     * Lists all PhoneModels models.
     * @return mixed
    if no phone make is selected, pick 1 by default
	 */
	   
    public function actionIndex($id=1)
    {
		$model = PhoneMakes::findOne($id);
        $searchModel = new PhoneModelsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andFilterWhere(['=','phone_make_id',$id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' 		=> $model,
			'id'  			=> $id,
        ]);
    }

    /**
     * Displays a single PhoneModels model.
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
     * Creates a new PhoneModels model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new PhoneModels();
		$modelphonemakes = PhoneMakes::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
				'modelphonemakes' => $modelphonemakes,
            ]);
        }
		
		/*
		if ($model->load(Yii::$app->request->post())) {
			$model->save();
			if ($model->hasErrors()) {
				print_r($model->getErrors());
				//echo 'dsfsdf failure';
			} else {
				echo 'validation succeeds';
				//return $this->redirect(['index-charges-per-category', 'id' => $id]);
			}
						
        } 
		*/
		
    }

    /**
     * Updates an existing PhoneModels model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $make)
    {
        $model = $this->findModel($id);
		$modelphonemakes = PhoneMakes::findOne($make);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
				'modelphonemakes' => $modelphonemakes,
            ]);
        }
    }

    /**
     * Deletes an existing PhoneModels model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $make)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index', 'id' => $make]);
    }

    /**
     * Finds the PhoneModels model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PhoneModels the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PhoneModels::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
