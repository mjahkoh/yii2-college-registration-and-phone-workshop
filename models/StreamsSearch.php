<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Streams;

/**
 * StreamsSearch represents the model behind the search form about `app\models\Streams`.
 */
class StreamsSearch extends Streams
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['stream', 'year'], 'safe'],
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
        $query = Streams::find();

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
            'id' => $this->id,
			'stream' => $this->stream,
            'year' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'stream', $this->stream])
		->andFilterWhere(['like', 'year', $this->year])
		;

        return $dataProvider;
    }
}
