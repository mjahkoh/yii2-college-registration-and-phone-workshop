<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Jobs;

/**
 * JobsSearch represents the model behind the search form of `app\models\Jobs`.
 */
class JobsSearch extends Jobs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'phone_make_id', 'phone_model_id', 'client_id', 'status', 'currency'], 'integer'],
            [['problem', 'date_job_commenced', 'date_job_completed', 'staff_allocated_id', 'message_status'], 'safe'],
            [['charges'], 'number'],
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
        $query = Jobs::find();

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
            'phone_make_id' => $this->phone_make_id,
            'phone_model_id' => $this->phone_model_id,
            'client_id' => $this->client_id,
            'charges' => $this->charges,
            'date_job_commenced' => $this->date_job_commenced,
            'date_job_completed' => $this->date_job_completed,
            'status' => $this->status,
            'currency' => $this->currency,
        ]);

        $query->andFilterWhere(['like', 'problem', $this->problem])
            ->andFilterWhere(['like', 'staff_allocated_id', $this->staff_allocated_id])
            ->andFilterWhere(['like', 'message_status', $this->message_status]);

        return $dataProvider;
    }
}
