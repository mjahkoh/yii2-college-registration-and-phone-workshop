<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Companies;

/**
 * CompaniesSearch represents the model behind the search form about `app\models\Companies`.
 */
class CompaniesSearch extends Companies
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'tel', 'mobile', 'mobile2'], 'integer'],
            [['company_name', 'address', 'date_created', 'logo', 'slogan', 'physical_location', 'facebook_handle', 'tweeter_handle', 'email'], 'safe'],
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
        $query = Companies::find();

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
            'status' => $this->status,
            'date_created' => $this->date_created,
            'tel' => $this->tel,
            'mobile' => $this->mobile,
            'mobile2' => $this->mobile2,
        ]);

        $query->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'slogan', $this->slogan])
            ->andFilterWhere(['like', 'physical_location', $this->physical_location])
            ->andFilterWhere(['like', 'facebook_handle', $this->facebook_handle])
            ->andFilterWhere(['like', 'tweeter_handle', $this->tweeter_handle])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
