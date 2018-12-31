<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\payments;

/**
 * PaymentsSearch represents the model behind the search form about `app\models\payments`.
 */
class PaymentsSearch extends payments
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'amount'], 'integer'],
			[['date_of_payment' , 'jobid', 'job_id'], 'safe'],
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
        $query = payments::find();

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
			'job_id' => $this->jobid,
            'amount' => $this->amount,
			'date_of_payment' => $this->date_of_payment,
        ]);

        return $dataProvider;
    }
}
