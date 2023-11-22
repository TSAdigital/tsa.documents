<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EventSearch represents the model behind the search form of `app\models\Event`.
 */
class EventSearch extends Event
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],

            ['name', 'string'],
            ['name', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['name', 'trim'],
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
        $query = Event::find();

        if(!Yii::$app->user->can('admin')){
            $user_id = Yii::$app->user->identity->id;
            $query->where(['user_id' => $user_id])->orWhere(['like', 'resolution', sprintf('"%s"', $user_id)])->andWhere(['status' => Event::STATUS_ACTIVE])->orWhere(['resolution' => NULL])->andWhere(['status' => Event::STATUS_ACTIVE]);
        }

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
            'status' => $this->status,

        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
