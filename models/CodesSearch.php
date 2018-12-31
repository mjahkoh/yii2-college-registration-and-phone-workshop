<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Codes;

/**
 * CodesSearch represents the model behind the search form of `app\models\Codes`.
 */
class CodesSearch extends Codes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'company_tel', 'company_phone'], 'integer'],
            [['code', 'company_name', 'company_physical_location', 'company_email', 'company_facebook_handle', 'company_website_url'], 'safe'],
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
        $query = Codes::find();

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
            'company_tel' => $this->company_tel,
            'company_phone' => $this->company_phone,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'company_physical_location', $this->company_physical_location])
            ->andFilterWhere(['like', 'company_email', $this->company_email])
            ->andFilterWhere(['like', 'company_facebook_handle', $this->company_facebook_handle])
            ->andFilterWhere(['like', 'company_website_url', $this->company_website_url]);

        return $dataProvider;
    }
}
