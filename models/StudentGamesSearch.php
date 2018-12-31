<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StudentGames;

/**
 * StudentGamesSearch represents the model behind the search form about `app\models\StudentGames`. 
 */
class StudentGamesSearch extends StudentGames
{ 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'gamesid'], 'integer'],
			[['studentid' ], 'safe'], 
        ];
    } 
 
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
	 
    public function search($params) 
    {
        $query = StudentGames::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query
			->joinWith('members', false, 'INNER JOIN')
			->groupBy('studentid')
			->orderBy('firstname ASC, middlename ASC, surname ASC'),
			'sort' => [
				// Set the default sort by name ASC and created_at DESC.
				'defaultOrder' => [
					'members.firstname' => SORT_ASC, 
					'members.middlename' => SORT_ASC, 
					'members.surname' => SORT_ASC, 
					//'created_at' => SORT_DESC
				],
			],			
			
			
			/**/
        ]);
		//var_dump($query);

		$dataProvider->setSort([
			'attributes'=>[
				//'studentid',
				'studentid'=>[
					'asc'=>['firstname'=>SORT_ASC, 'middlename'=>SORT_ASC, 'surname'=>SORT_ASC],
					'desc'=>['firstname'=>SORT_DESC, 'middlename'=>SORT_DESC, 'surname'=>SORT_DESC],
					'label'=>'Full Name',
					'default'=>SORT_ASC
				],
			]
		]);

    	if (!($this->load($params) && $this->validate())) {
			//var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
			//var_dump($query);
			return $dataProvider;
		}
		
		//$query -> joinWith(['members']) ;

        // grid filtering conditions
        /*
		$query->andFilterWhere([
            'fullname' => $this->fullname,
        ]);
		*/
		//$query->andFilterWhere(['like', 'members.fullname', $this->studentid]);
		/**/
		
		//$query->andFilterWhere(['=', 'studentid', $this->studentid]);
		
		$query->andWhere('firstname LIKE "%' . $this->studentid . '%" ' .
			'OR middlename LIKE "%' . $this->studentid . '%"'   .
			'OR surname LIKE "%' . $this->studentid . '%"'   
		);
		
		//print_r($this->membersid);exit;
		//var_dump('hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh');
		//var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
        return $dataProvider;
    }
}
