<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Book;

/**
 * BookSearch represents the model behind the search form about `app\models\Book`.
 */
class BookSearch extends Book
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['book_code', 'synopsis', 'color', 'publish_date', 'name', 'author_id'], 'safe'],
            [['sell_amount', 'buy_amount'], 'number'],
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
        $query = Book::find()->indexBy('id');

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
			'name' => $this->name,
            'publish_date' => $this->publish_date,
            'sell_amount' => $this->sell_amount,
            'buy_amount' => $this->buy_amount,
            'status' => $this->status,
            'author_id' => $this->author_id,
        ]);

        $query->andFilterWhere(['like', 'book_code', $this->book_code])
            ->andFilterWhere(['like', 'synopsis', $this->synopsis])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
