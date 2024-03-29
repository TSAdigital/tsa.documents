<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],

            ['email', 'email'],
            ['email', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

            [['roles', 'username', 'active'], 'string'],
            [['roles', 'username', 'active'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['roles', 'username', 'active'], 'trim'],
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
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        $query->joinWith(['userRoles']);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $dataProvider->setSort([
            'attributes' => [
                'roles' => [
                    'asc' => ['auth_assignment.item_name' => SORT_ASC, 'id' => SORT_ASC],
                    'desc' => ['auth_assignment.item_name' => SORT_DESC, 'id' => SORT_DESC],
                    'label' => 'roles',
                    'default' => SORT_ASC
                ],
                'active' => [
                    'asc' => ['active' => SORT_ASC, 'id' => SORT_ASC],
                    'desc' => ['active' => SORT_DESC, 'id' => SORT_DESC],
                    'label' => 'active',
                    'default' => SORT_ASC
                ],
                'username',
                'email',
                'status' => [
                    'asc' => ['status' => SORT_ASC, 'id' => SORT_ASC],
                    'desc' => ['status' => SORT_DESC, 'id' => SORT_DESC],
                    'label' => 'status',
                    'default' => SORT_ASC
                ],
            ]
        ]);

        $query->andFilterWhere([
            'status' => $this->status,
            'email' => $this->email,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_assignment.item_name', $this->roles])
            ->andFilterWhere(['>=', 'active', $this->active ? strtotime($this->active . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'active', $this->active ? strtotime($this->active . ' 23:59:59') : null]);
        return $dataProvider;
    }
}
