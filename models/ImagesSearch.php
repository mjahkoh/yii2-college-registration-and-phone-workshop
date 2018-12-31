<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Images;

/**
 * ImagesSearch represents the model behind the search form of `app\models\Images`. 
 */
class ImagesSearch extends Images
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['filename', 'filelocation'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Images::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

    	if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}
		$query->joinWith('imagesDetails');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
			//'filelocation' => $this->filelocation,
        ]);

        $query->andFilterWhere(['like', 'filename', $this->filename])
			->andFilterWhere(['like', 'imagesdetails.filelocation', $this->filelocation]);

        return $dataProvider;
    }
}
