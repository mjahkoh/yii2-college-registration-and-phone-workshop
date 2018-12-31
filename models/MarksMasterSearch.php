<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MarksMaster;

/**
 * MarksMasterSearch represents the model behind the search form about `app\models\MarksMaster`.
 */
class MarksMasterSearch extends MarksMaster
{
	public $unitsName, $fullName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'members_id', 'unit', 'total_marks', ', class'], 'integer'],
            [['date_of_exam', 'unitsName', 'fullName'], 'safe'],
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
        $query = MarksMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider->setSort([
			'attributes'=>[
				'unitsName' => [
					'asc' => ['units.unit' =>SORT_ASC ] ,
					'desc' => ['units.unit'=> SORT_DESC ],
					'label' => "Unit's Name"
				],
				'fullName'=>[
					'asc'=>['firstname'=>SORT_ASC, 'middlename'=>SORT_ASC, 'surname'=>SORT_ASC],
					'desc'=>['firstname'=>SORT_DESC, 'middlename'=>SORT_DESC, 'surname'=>SORT_DESC],
					'label'=>'Full Name',
					'default'=>SORT_ASC
				],
				'id',
				'unit',
				'class',
				'members_id',
				'date_of_exam',
				'total_marks',
			]	
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

        // grid filtering conditions
        $query->andFilterWhere([
            'units.unit' => $this->unitsName,
			//'fullName' => $this->fullName,
			//'firstname' => $this->firstname,
			'members.firstname' => $this->fullName,
			'members.middlename' => $this->fullName,
			'members.surname' => $this->fullName,
            'members_id' => $this->members_id,
            'date_of_exam' => $this->date_of_exam,
            'unit' => $this->unit,
			'class' => $this->class,
            'total_marks' => $this->total_marks,
        ]);

        $query->andFilterWhere(
			['like', 'members_id', $this->members_id])
            ->andFilterWhere(['like', 'units.unit', $this->unitsName])
            ->andFilterWhere(['like', 'members.firstname', $this->fullName])
			->andFilterWhere(['like', 'members.middlename', $this->fullName])
			->andFilterWhere(['like', 'members.surname', $this->fullName])
            ->andFilterWhere(['like', 'date_of_exam', $this->date_of_exam])
			->andFilterWhere(['like', 'class', $this->class])
			->andFilterWhere(['like', 'total_marks', $this->total_marks])
			;

		$query->joinWith(['units' => function ($q) {$q-> where ('units.unit LIKE "%' . $this->unitsName . '%"') ;}]) ;
		$query->joinWith(['members' => function ($q) {$q-> where 
		(
		' members.firstname LIKE "%' . $this->fullName . '%"'.
		' OR members.middlename LIKE "%' . $this->fullName . '%" ' .
		' OR members.surname LIKE "%' . $this->fullName . '%" ' 
		) ;}]) ;
        return $dataProvider;
    }
}
