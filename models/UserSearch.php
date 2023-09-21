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

            [['roles', 'username'], 'string'],
            [['roles', 'username'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['roles', 'username'], 'trim'],
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
                    'asc' => ['auth_assignment.item_name' => SORT_ASC],
                    'desc' => ['auth_assignment.item_name' => SORT_DESC],
                    'label' => 'roles',
                ],
                'username',
                'email',
                'status',
            ]
        ]);

        $query->andFilterWhere([
            'status' => $this->status,
            'email' => $this->email,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_assignment.item_name', $this->roles]);

        return $dataProvider;
    }
}
