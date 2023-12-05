<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TelegramSearch represents the model behind the search form of `app\models\Telegram`.
 */
class TelegramSearch extends Telegram
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['telegram', 'string', 'max' => 255],
            ['telegram', 'trim'],
            ['telegram', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

            ['username', 'string', 'max' => 255],
            ['username', 'trim'],
            ['username', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

            ['first_name', 'string', 'max' => 255],
            ['first_name', 'trim'],
            ['first_name', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

            ['last_name', 'string', 'max' => 255],
            ['last_name', 'trim'],
            ['last_name', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
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
        $query = Telegram::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort'=> [
                'defaultOrder' => ['id' => SORT_DESC]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'telegram', $this->telegram])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name]);

        return $dataProvider;
    }
}
