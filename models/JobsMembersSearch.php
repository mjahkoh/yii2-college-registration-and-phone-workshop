<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JobsMembers;

/**
 * JobsMembersSearch represents the model behind the search form about `app\models\JobsMembers`.
 */
class JobsMembersSearch extends JobsMembers
{ 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'national_id'], 'integer'],
            [['name', 'category', 'password_reset_token','username', 'authkey', 'password', 'password_reset_token', 'email', 'telephone', 'status', 'memberscat2'], 'safe'],
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
        $query = JobsMembers::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		 $dataProvider->setSort([
			'defaultOrder'	=> ['name'=>SORT_ASC],
			'attributes'=>[
				'name',
				'telephone'=>[
					'asc'=> ['members.tel'=>SORT_ASC,  'members.tel_prefix'=>SORT_ASC],
					'desc'=>['members.tel'=>SORT_ASC, 'members.tel_prefix'=>SORT_ASC],
					'label'=>'Telephone',
					'default'=>SORT_ASC
				],
				'mobile',
				'tel',
				'national_id',
				'status',
				'category',
			]
		]);		

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

        // grid filtering conditions
        //$category = JobsMembers::STAFF_MEMBER ? 'JobsMembers Member' : 'Clientelle' ;
		/**/
		if ($this->status === "Active" ) {
			$status = JobsMembers::STATUS_ACTIVE;
		} elseif ($this->status === "Disabled" ) {
			$status = JobsMembers::STATUS_INACTIVE;
		} elseif ($this->status === "Deleted" ) {
			$status = JobsMembers::STATUS_DELETED;
		} else {
			$status = $this->status;
		}
		
		$category =  ( $this->category === "Staff") ? JobsMembers::STAFF_MEMBER : JobsMembers::CLIENTELLE;
		
		$query->andFilterWhere([
            'id' => $this->id,
            //'status' => $status,
			'telephone' => $telephone,
			'mobile' => $mobile,
			'category' => $category,
        ]);

        $query
		->andFilterWhere(['like', 'name', $this->name])
		->andFilterWhere(['like', 'category', $category])
		->andFilterWhere(['like', 'telephone', $telephone])
		->andFilterWhere(['like', 'mobile', $mobile])
		->andFilterWhere(['like', 'national_id', $this->national_id])
		;

		/* Setup your custom filtering criteria */
		// filter by person full name
		$query->andWhere('tel_prefix LIKE "%' . $this->telephone . '%" ' .
			'OR tel LIKE "%' . $this->telephone . '%"'
		);		
        return $dataProvider;
    }
}
