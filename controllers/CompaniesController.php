<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use app\helpers\Setup;
use app\models\Companies;
use app\models\CompaniesSearch;
/**
 * CompaniesController implements the CRUD actions for Companies model.
 */
class CompaniesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'delete' , 'index'],
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
     * Lists all Companies models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompaniesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Companies model.
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
     * Creates a new Companies model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Companies();

        if ($model->load(Yii::$app->request->post()) ) {
			
			
			$model->save();
			if ($model->hasErrors()) {
				print_r($model->getErrors());
				exit;
				//echo 'dsfsdf failure';
			} else {
				echo 'validation succeeds';
			}
		   return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Companies model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
			if ($model->hasErrors()) {
				print_r($model->getErrors());
				//echo 'dsfsdf failure';
			} else {
				echo 'validation succeeds';
				//return $this->redirect(['index-charges-per-category', 'id' => $id]);
			}
			//$model->logo = $logo;
			$model->save();
		    return $this->redirect(['view', 'id' => $model->id]);
        } else {
			return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Companies model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        //return $this->redirect(['index']);
    }

    /**
     * Finds the Companies model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Companies the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Companies::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionUpload()
	{
		$fileName = 'file';
		$uploadPath = Yii::getAlias('@uploads'); // Alias root_dir/uploads
		if (isset($_FILES[$fileName])) {
			$file = \yii\web\UploadedFile::getInstanceByName($fileName);
	
			//Print file data
			//print_r($file);
	
			if ($file->saveAs($uploadPath . '/' . $file->name)) {
				//Now save file data to database
	
				echo \yii\helpers\Json::encode($file);
			}
		} else {
			return $this->render('upload');
		}
	
		return false;
	}	
	
	public function actionRemoveFiles()
	{
	
	}
	
}
