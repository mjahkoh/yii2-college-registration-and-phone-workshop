<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Events;
use app\models\EventsSearch;


/**
 * EventsController implements the CRUD actions for Events model.
 */
class EventsController extends Controller
{
    /**
     * {@inheritdoc}
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
     * Lists all Events models.
     * @return mixed
     */
    public function actionIndex()
    {
        $events = Events::find()->all();
		$tasks = [];
		foreach ($events as $eve) {
		  $event = new \yii2fullcalendar\models\Event();
		  $event->id = $eve->id;
		  $event->title = $eve->title;
		  $event->start = $eve->date_created;
		  $tasks[] = $event;		
		}
		
        return $this->render('index', [
            'events' => $tasks,
        ]);
    }

    /**
     * Displays a single Events model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Events model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($date, $createnew = 0)
    {
        //to avoid a continuos loop , set createnew to true to ensure we go straight to the
		// create new model.  this is called from the update model
		// just in case we need ta add an event to the said date
		if ($createnew == 0) {
			//first find all or any models on the said date. If Available, 
			//raise a popup with all the available models so the user chooses/which to update
			$allEventsModel = Events::find()->where(['date_created' => $date])->limit(1)->one();
			//print_r($allEventsModel);
			if ($allEventsModel  !== null ) {
				//echo "iko kitu<br>";
				return $this->indexEvents($date);
			} else {
				//echo "hakuna<br>";	
			}
		}
			//exit;
		//echo "creafffffffffffffffffffffffffte";
		$model = new Events();
		$model->date_created = $date;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Events model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		//print_r("update mode");exit;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
           // return $this->redirect(['view', 'id' => $model->id]);
		   return $this->redirect(['index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Events model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Events model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Events the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Events::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
    /**
     * Lists all Authors models.
     * @return mixed
     */
    public function indexEvents($date)
    {
        $searchModel = new EventsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['date_created'=> $date]);
        return $this->renderAjax('indexEvents', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'date' => $date,
        ]);
    }

}
