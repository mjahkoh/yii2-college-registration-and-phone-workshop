<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

use app\models\Authors;
use app\models\AuthorsSearch;
/**
 * AuthorsController implements the CRUD actions for Authors model.
 */
class AuthorsController extends Controller
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
		if (Yii::$app->user->can('indexAuthors')) {
			$searchModel = new AuthorsSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	
			return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		} else {
			 throw new ForbiddenHttpException('You are not allowed to Index this page');
		}
    }

    /**
     * Displays a single Authors model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		if (Yii::$app->user->can('viewAuthors')) {
			return $this->render('view', [
				'model' => $this->findModel($id),
			]);
		} else {
			 throw new ForbiddenHttpException('You are not allowed to View an Author');
		}
    }

    /**
     * Creates a new Authors model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		if (Yii::$app->user->can('createAuthors')) {
			$model = new Authors();
	
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->author_id]);
			} else {
				return $this->render('create', [
					'model' => $model,
				]);
			}
		} else {
			 throw new ForbiddenHttpException('You are not allowed to Create an Author');
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
		if (Yii::$app->user->can('updateAuthors')) {
			$model = $this->findModel($id);
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->author_id]);
			} else {
				return $this->render('update', [
					'model' => $model,
				]);
			}
		} else {
			 throw new ForbiddenHttpException('You are not allowed to Update this Author');
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
		if (Yii::$app->user->can('deleteAuthors')) {
			$this->findModel($id)->delete();
			return $this->redirect(['index']);
		} else {
			 throw new ForbiddenHttpException('You are not allowed to Delete this Author');
		}
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
}
