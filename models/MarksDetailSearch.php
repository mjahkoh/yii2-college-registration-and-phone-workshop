<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MarksDetail;

/**
 * MarksDetailSearch represents the model behind the search form about `app\models\MarksDetail`.
 */
class MarksDetailSearch extends MarksDetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'student_id', 'marks', 'marks_master_id', 'rating'], 'integer'],
            [['last_date_of_exam'], 'safe'],
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
        $query = MarksDetail::find();

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
            'student_id' => $this->student_id,
            'marks' => $this->marks,
            'marks_master_id' => $this->marks_master_id,
            'rating' => $this->rating,
            'last_date_of_exam' => $this->last_date_of_exam,
        ]);

        return $dataProvider;
    }
}
