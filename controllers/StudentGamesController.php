<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

use app\models\StudentGames;
use app\models\StudentGamesSearch;
use app\models\Members;
use app\models\Games;
/**
 * StudentGamesController implements the CRUD actions for StudentGames model.
 */
class StudentGamesController extends Controller
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
     * Lists all StudentGames models.
     * @return mixed
     */
    public function actionIndex()
    {
        
		$searchModel = new StudentGamesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$dataProvider->query->andWhere(['company_payments_id'=> $id]);
		/*
		$dataProvider = new ActiveDataProvider([
			'query' => StudentGames::find()
			->orderBy('firstname ASC, middlename ASC, surname ASC')
			->joinWith('members', false, 'INNER JOIN')
			->groupBy('studentid'),
			'pagination' => [
				'pageSize' => 20,
			],
		]);	*/	
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StudentGames model.
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
     * Creates a new StudentGames model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StudentGames();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            
			$dataProvider = new ActiveDataProvider([
				'query' => Games::find()
				->orderBy('game'),
				'pagination' => [
					'pageSize' => 20,
				],
			]);
			
			return $this->render('create', [
                'model' => $model,
				'dataProvider' => $dataProvider,
            ]);
        }
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
				
				$keys = Yii::$app->request->post('selection');
				$transaction = Yii::$app->db->beginTransaction();
				try {
					//print_r($model->studentid);
					//exit;
					StudentGames::deleteAll('studentid = :studentid ', [':studentid' => $model->studentid]);
					//print_r($keys);exit;
					if ($keys && is_array($keys)  && count($keys)){
					
						foreach ($keys as $value) {
							// INSERT (table name, column values)
							Yii::$app->db->createCommand()->insert('student_games', [
								'gamesid' => $value,
								'studentid' =>  $model->studentid,
							])
							->execute();
						}			
					}
					$transaction->commit();
					return $this->redirect(['index-games-played', 'id'=> $model->studentid]);
					
				} catch (Exception $e) {
					$transaction->rollback();
				}

			
        } else {
			//$model = new StudentGames();
			$studentid = $model->studentid;
			$count = Yii::$app->db->createCommand("
				SELECT COUNT(*) FROM student_games   right JOIN games ON (student_games.gamesid=games.id) WHERE student_games.studentid=:studentid", [':studentid' => $studentid])->queryScalar();			
			//echo "count: $count<br>";exit;
			$dataProvider = new SqlDataProvider([
				'sql' => "SELECT games.*, if ((select gamesid from  student_games where student_games.gamesid=games.id and student_games.studentid=:studentid),1,0) as gamesid FROM games order by games.game",	
				'params' => [':studentid' => $studentid],
				'totalCount' => $count,
				'key'        => 'gamesid',  //
				'sort' => [
					'attributes' => [
						'games.game',
						'games.id',
						'student_games.gamesid',
					],
				],
				'pagination' => [
					'pageSize' => 20,
				],
			]);			
			return $this->render('update', [
                'model' => $model,
				'dataProvider' => $dataProvider,
            ]);
        }
    }


    /**
	param id 
     */
    public function actionUpdateStudent($id)
    {
	
        if (Yii::$app->request->post()) {
			$model = new StudentGames();
			if ($model->load(Yii::$app->request->post()) ) {
				$keys = Yii::$app->request->post('selection');
				if ($keys && is_array($keys)  && count($keys)){
					foreach ($keys as $value) {
						// INSERT (table name, column values)
						Yii::$app->db->createCommand()->insert('student_games', [
							'gamesid' => $value,
							'studentid' =>  $model->studentid,
						])
						->execute();
					}			
				}

				return $this->redirect(['index-games-played', 'id' => $id]);
			}		
			$dataProvider = new ActiveDataProvider([
				'query' => Games::find()
				->orderBy('game'),
				'pagination' => [
					'pageSize' => 20,
				],
			]);
        } else {
		
			$model = StudentGames::find()
			->joinWith('members', false, 'INNER JOIN')
			->where(['=','members.id', $id])
			->one();
			//print_r($model);
			
			//if the moodel is null the students games hasnt been set, present an empty gridsheet
			if ($model !== null) {
				$studentid = $model->studentid;
				$count = Yii::$app->db->createCommand("
					SELECT COUNT(*) FROM student_games   right JOIN games ON (student_games.gamesid=games.id) WHERE student_games.studentid=:studentid", [':studentid' => $studentid])->queryScalar();			
				//echo "count: $count<br>";exit;
				$dataProvider = new SqlDataProvider([
					'sql' => "SELECT games.*, if ((select gamesid from  student_games where student_games.gamesid=games.id and student_games.studentid=:studentid),1,0) as gamesid FROM games order by games.game",	
					'params' => [':studentid' => $studentid],
					'totalCount' => $count,
					'key'        => 'gamesid',  //
					'sort' => [
						'attributes' => [
							'games.game',
							'games.id',
							'student_games.gamesid',
						],
					],
					'pagination' => [
						'pageSize' => 20,
					],
				]);			
				
			} else {
			
				$model = new StudentGames();
				$dataProvider = new ActiveDataProvider([
					'query' => Games::find()
					->orderBy('game'),
					'pagination' => [
						'pageSize' => 20,
					],
				]);
			}
			
        }
		return $this->render('create', [
			'model' => $model,
			'dataProvider' => $dataProvider,
		]);
    }

    /**
     * Deletes an existing StudentGames model.
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
     * Finds the StudentGames model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StudentGames the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StudentGames::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
    //$id is the membersID
	public function actionIndexGamesPlayed($id)
    {

		$searchModel = new StudentGamesSearch();
       // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$dataProvider->query->andWhere(['company_payments_id'=> $id]);
		$dataProvider = new ActiveDataProvider([
			'query' => StudentGames::find()
			->where(['studentid' => $id])
			->orderBy('gamesid'),
			'pagination' => [
				'pageSize' => 20,
			],
		]);
        $model = Members::findOne($id);
		$studentgamesmodel = StudentGames::find()
		->where(['studentid' => $id])
		->one();
		return $this->render('indexGamesPlayed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
			'studentgamesmodel' => $studentgamesmodel,
        ]);
	}	
}
