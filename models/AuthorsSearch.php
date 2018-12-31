<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Authors;

/**
 * AuthorsSearch represents the model behind the search form about `app\models\Authors`.
 */
class AuthorsSearch extends Authors
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_id'], 'integer'],
            [['name', 'status'], 'safe'],
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
        $query = Authors::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'author_id' => $this->author_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
		
		/*$query->andFilterWhere(
			'status LIKE "%' . $this->fullName . '%" '
		);		
		*/
		//print_r($this->status);
        return $dataProvider;
    }
}
