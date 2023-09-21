<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DocumentSearch represents the model behind the search form of `app\models\Document`.
 */
class DocumentSearch extends Document
{
    public $date_from;
    public $date_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DRAFT]],

            ['type', 'in', 'range' => [self::TYPE_INCOMING, self::TYPE_OUTGOING, self::TYPE_INTERNAL]],

            [['name', 'number'], 'string'],
            [['name', 'number'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['name', 'number', 'date'], 'trim'],

            ['user_id', 'integer'],

            [['date_from', 'date_to', 'date'], 'date'],
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
        $query = Document::find();

        if(!Yii::$app->user->can('admin')){
            $user_id = Yii::$app->user->identity->id;
            $query->where(['like', 'resolution', sprintf('"%s"', $user_id)])->andWhere(['status' => [Document::STATUS_ACTIVE, Document::STATUS_INACTIVE]])->orWhere(['resolution' => NULL, 'status' => [Document::STATUS_ACTIVE, Document::STATUS_INACTIVE]])->orWhere(['user_id' => $user_id])->orWhere(['executor_id' => $user_id, 'status' => Document::STATUS_ACTIVE]);
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

        $query->andFilterWhere([
            'number' => $this->number,
            'date' => $this->date ? date('Y-m-d', strtotime($this->date)) : null,
            'type' => $this->type,
            'status' => $this->status,
        ]);

        $query->andFilterWhere([
                'OR',
                ['like', 'name', $this->name],
                ['like', 'uniq_id', $this->name],
            ])
            ->andFilterWhere(['>=', 'date', $this->date_from ? date('Y-m-d', strtotime($this->date_from)) : null])
            ->andFilterWhere(['<=', 'date', $this->date_to ? date('Y-m-d', strtotime($this->date_to)) : null]);
        return $dataProvider;
    }
}