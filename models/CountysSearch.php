<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Countys;

/**
 * CountysSearch represents the model behind the search form about `app\models\Countys`.
 */
class CountysSearch extends Countys
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['county', 'state_id'], 'safe'],
            [['id'], 'integer'],
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
        $query = Countys::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['states']) ;

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'uscitiesv_countys.state_id' => $this->state_id,
        ]);

        $query->andFilterWhere(['like', 'county', $this->county])
			  ->andFilterWhere(['=', 'uscitiesv_countys.state_id', $this->state_id]);

        return $dataProvider;
    }
}
