<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MenuSearch represents the model behind the search form of `app\models\Menu`.
 */
class OrderSearch extends Order
{
    public $totalRounding;
    public $totalTransaction;
    public $tax;
    public $beforeRounding;
    public $subTotal;
    public $changes;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['date', 'ordered_by'], 'required'],
            [['id', 'is_paid'], 'integer'],
            [['date', 'created_at', 'updated_at', 'order_code', 'total'], 'safe'],
            [['ordered_by'], 'string', 'max' => 255],
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
        $query = Order::find();

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
        $query->andFilterWhere(['is_paid' => 1]);

        if ($this->date) $query->andFilterWhere(['date' => $this->date]);

        // $query->andFilterWhere(['like', 'order_code', $this->order_code]);

        return $dataProvider;
    }
}
