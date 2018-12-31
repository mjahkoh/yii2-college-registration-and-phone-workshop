<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Branches;

/**
 * BranchesSearch represents the model behind the search form about `app\models\Branches`.
 */
class BranchesSearch extends Branches
{
    /**
     * @inheritdoc
     */
    public function rules() 
    {
        return [
            [['id'], 'integer'],
            [['branch_name', 'address', 'date_created', 'location', 'companies_company_id', 'status'], 'safe'],
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
        
		$query = Branches::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
				// Set the default sort by name ASC and created_at DESC.
				'defaultOrder' => [
					'branch_name' => SORT_ASC, 
					'date_created' => SORT_DESC,
				]
			],			
        ]);

    	if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}
		$this->date_created =  Yii::$app->formatter->asDate($this->date_created,'yyyy-MM-dd');

		$query->joinWith('companies');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'branches.address' => $this->address,
			//'companies_company_id' => $this->companies_company_id,
        ]);
		
		if (isset($this->date_created)) {
			//echo "isset";
			$query->andFilterWhere([
				'branches.date_created' => $this->date_created,
			]);
		} else {
			//unset the date_created
			echo "isnt set";
			unset($this->date_created);
		}
		
		//$status = $this->status == 'Active' ? 1 : 2;
        $query->andFilterWhere(['like', 'branch_name', $this->branch_name])
            ->andFilterWhere(['like', 'branches.address', $this->address])
			->andFilterWhere(['=', 'branches.date_created', $this->date_created])
			->andFilterWhere(['=', 'branches.status', $this->status])
            ->andFilterWhere(['like', 'companies.company_name', $this->companies_company_id]);
			
        return $dataProvider;
    }
}
