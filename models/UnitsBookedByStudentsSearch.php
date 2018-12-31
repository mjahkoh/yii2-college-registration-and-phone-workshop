<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UnitsBookedByStudents;

/**
 * UnitsBookedByStudentsSearch represents the model behind the search form about `app\models\UnitsBookedByStudents`.
 */
class UnitsBookedByStudentsSearch extends UnitsBookedByStudents
{
    public $unitName ;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'unit_id', 'semester', 'student_id'], 'integer'],
            [['academic_year', 'unitName'], 'safe'],
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
        $query = UnitsBookedByStudents::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider-> setSort([
		'attributes' => [
			'id' ,
			'unitName' => [
				'asc' => ['units.unit' =>SORT_ASC ] ,
				'desc' => ['units.unit'=> SORT_DESC ],
				'label' => "Unit"
				],
			'unit_id',
			'semester',
			'academic_year',
			'student_id',
		]
		]) ;

        if (!($this->load($params) && $this->validate())) {
        //if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query -> joinWith([ 'units' ]) ;
			return $dataProvider;
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'unit_id' => $this->unit_id,
            'semester' => $this->semester,
            'academic_year' => $this->academic_year,
            'student_id' => $this->student_id,
        ]);

		$query->joinWith(['units' => function ($q) {$q-> where ('units.unit LIKE "%' . $this->unitName . '%"') ;}]) ;

        return $dataProvider;
    }
}
