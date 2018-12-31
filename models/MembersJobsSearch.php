<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MembersJobs;

/**
 * MembersJobsSearch represents the model behind the search form about `app\models\MembersJobs`.
 */
class MembersJobsSearch extends MembersJobs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['name', 'category', 'password_reset_token','username', 'authkey', 'password', 'password_reset_token', 'email'], 'safe'],
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
        $query = MembersJobs::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		 $dataProvider->setSort([
			'defaultOrder'	=> ['name'=>SORT_ASC],
			'attributes'=>[
				'name',
				'tel',
			]
		]);		

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

        // grid filtering conditions
        //$category = MembersJobs::STAFF_MEMBER ? 'Members Member' : 'Clientelle' ;
		$query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
			'category' => $this->category,
        ]);

        $query
		->andFilterWhere(['like', 'name', $this->name])
		->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
		->andFilterWhere(['like', 'category', $this->category])
		;

        return $dataProvider;
    }
}
