<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TaskSearch represents the model behind the search form of `app\models\Task`.
 */
class TaskSearch extends Task
{
    public $date_from;
    public $date_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'string'],
            ['name', 'trim'],

            ['description', 'string'],
            ['description', 'trim'],

            [['date', 'date_from', 'date_to'], 'date', 'format' => 'php:d.m.Y'],
            [['date', 'date_from', 'date_to'], 'trim'],

            ['priority', 'in', 'range' => [self::PRIORITY_LOW, self::PRIORITY_MIDDLE, self::PRIORITY_HIGH]],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DRAFT]],
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
        $query = Task::find();

        if(!Yii::$app->user->can('admin')){
            $user_id = Yii::$app->user->identity->id;
            $query->where(['like', 'resolution', sprintf('"%s"', $user_id)])->andWhere(['status' => [Task::STATUS_ACTIVE, Task::STATUS_INACTIVE]])->orWhere(['resolution' => NULL, 'status' => [Task::STATUS_ACTIVE, Task::STATUS_INACTIVE]])->orWhere(['user_id' => $user_id])->orWhere(['executor_id' => $user_id, 'status' => Document::STATUS_ACTIVE]);
        }

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
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'priority' => $this->priority,
            'status' => $this->status,
        ]);

        $query->andFilterWhere([
                'OR',
                ['like', 'name', $this->name],
                ['like', 'uniq_id', $this->name],
            ])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['>=', 'date', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'date', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null])
            ->andFilterWhere(['>=', 'date', $this->date ? strtotime($this->date . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'date', $this->date ? strtotime($this->date . ' 23:59:59') : null]);

        return $dataProvider;
    }
}