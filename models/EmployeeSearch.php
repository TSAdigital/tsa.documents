<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EmployeeSearch represents the model behind the search form of `app\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    public $position_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],

            ['birthdate', 'date'],

            [['last_name', 'first_name', 'middle_name', 'position_name'], 'string'],
            [['last_name', 'first_name', 'middle_name', 'position_name'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['last_name', 'first_name', 'middle_name', 'position_name'], 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
        $query = Employee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        $query->joinWith(['position']);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $dataProvider->setSort([
            'attributes' => [
                'position_name' => [
                    'asc' => ['positions.name' => SORT_ASC],
                    'desc' => ['positions.name' => SORT_DESC],
                    'label' => 'positions.name',
                    'default' => SORT_ASC
                ],
                'last_name',
                'first_name',
                'middle_name',
                'status',
            ]
        ]);

        $query->andFilterWhere([
            'birthdate' => !empty($this->birthdate) ? date('Y-m-d', strtotime($this->birthdate)) : NULL,
            'employee.status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'position.name', $this->position_name]);

        return $dataProvider;
    }
}
