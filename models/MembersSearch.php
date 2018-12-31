<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Members;

/**
 * MembersSearch represents the model behind the search form about `app\models\Members`.
 */
class MembersSearch extends Members
{

	public $fullName;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sex', 'marital_status', 'mobile', 'mobile_prefix', 'state', 'city', 'home_phone', 'savings'], 'integer'],
            [['username', 'email', 'nickname', 'pets_name', 'password_hash', 'auth_key', 'fullName', 'date_of_birth'], 'safe'],
			//[[], 'safe']//, 'favourite_companion'
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
        $query = Members::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
				// Set the default sort by  SORT_DESC and SORT_ASC.
				'defaultOrder' => [
					'firstname' => SORT_ASC, 
					'middlename' => SORT_ASC,
					'surname' => SORT_ASC,
				]
			],			
        ]);

		$dataProvider->setSort([
			'defaultOrder'	=> ['fullName'=>SORT_ASC],
			'attributes'=>[
				'fullName'=>[
					'asc'=>['firstname'=>SORT_ASC, 'middlename'=>SORT_ASC, 'surname'=>SORT_ASC],
					'desc'=>['firstname'=>SORT_DESC, 'middlename'=>SORT_DESC, 'surname'=>SORT_DESC],
					'label'=>'Full Name',
					'default'=>SORT_ASC
				],
				'username',
				'nickname',
				'mobile',
				'mobile_prefix',
			]
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'sex' => $this->sex,
            'marital_status' => $this->marital_status,
			'firstname' => $this->firstname,
			'middlename' => $this->middlename,
			'surname' => $this->surname,
            'mobile' => $this->mobile,
			'mobile_prefix' => $this->mobile_prefix,
            'state' => $this->state,
            'city' => $this->city,
            //'favourite_companion' => $this->favourite_companion,
            'home_phone' => $this->home_phone,
            'date_of_birth' => $this->date_of_birth,
			'savings' => $this->savings,
        ]);

        $query->andFilterWhere(
			['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'firstname', $this->firstname])
			->andFilterWhere(['like', 'middlename', $this->middlename])
			->andFilterWhere(['like', 'surname', $this->surname])
			->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'pets_name', $this->pets_name])
            ->andFilterWhere(['like', 'date_of_birth', $this->date_of_birth]);

		$query->andWhere(
			'firstname LIKE "%' . $this->fullName . '%" ' .
			'OR middlename LIKE "%' . $this->fullName . '%" ' .
			'OR surname LIKE "%' . $this->fullName . '%"'
		);		

        return $dataProvider;
    }
}
