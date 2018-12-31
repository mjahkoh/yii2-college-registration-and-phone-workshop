<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Citys;

/**
 * CitysSearch represents the model behind the search form about `app\models\Citys`.
 */
class CitysSearch extends Citys
{
	public $globalSearch;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id'], 'integer'],
            [['city', 'city_ascii', 'county_id', 'globalSearch','state', 'countys.state_id'], 'safe'],
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
        $query = Citys::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'city_id' => $this->city_id,
			'city' => $this->city,
			'city_ascii' => $this->city_ascii,
			'county_id' => $this->county_id,
			'countys.state_id' => $this->state,
        ]);
		
		$query->joinWith('countys');
		
        //$query->joinWith(['countys' => function ($q) {$q-> where ('uscitiesv_countys.id = uscitysv_citys.county_id' ) ;}]) ;
		//$query->joinWith(['countys' => function ($q) {$q-> where ('uscitiesv_countys.state_id = ' . $this->state ) ;}]) ;
		//if (strlen($this->state_id)) {
			//$query->joinWith(['countys' => function ($q) {$q-> where ('uscitiesv_countys.state_id = ' . $this->state ) ;}]) ;
		//}	

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

        // grid filtering conditions
        /*
		$query->andFilterWhere([
            'city_id' => $this->city_id,
            //'county_id' => $this->county_id,
        ]);
		*/

		if (strlen($this->globalSearch)) {
			$this->globalSearch = trim($this->globalSearch);
			$query->orFilterWhere(['like', 'city', $this->globalSearch])
				->orFilterWhere(['like', 'city_ascii', $this->globalSearch])
				->orFilterWhere(['like', 'uscitiesv_countys.county', $this->globalSearch]);
		} else {
			$query->andFilterWhere(['like', 'city', $this->city])
				->andFilterWhere(['like', 'city_ascii', $this->city_ascii])
				->andFilterWhere(['like', 'city_id', $this->city_id])
				->andFilterWhere(['=', 'uscitiesv_countys.state_id', $this->state])
				->andFilterWhere(['=', 'county_id', $this->county_id]);
		}
		
        return $dataProvider;
    }
}
