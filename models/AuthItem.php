<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string|null $description
 * @property string|null $rule_name
 * @property resource|null $data
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $children
 * @property AuthItem[] $parents

 */
class AuthItem extends ActiveRecord
{
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'max' => 64],
            ['name', 'unique'],

            ['description', 'string'],
            ['description', 'required'],
            ['description', 'unique'],

            ['type', 'integer'],
            ['type', 'required'],

            ['rule_name', 'string', 'max' => 64],

            ['data', 'string'],

            [['created_at', 'updated_at'], 'integer'],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['status', 'default', 'value'=> self::STATUS_ACTIVE],
            ['status', 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
            'type' => 'Тип',
            'description' => 'Наименование',
            'rule_name' => 'Наименование',
            'data' => 'Дата',
            'status' => 'Статус',
            'created_at' => 'Запись создана',
            'updated_at' => 'Запись обновлена',
        ];
    }

    /**
     * Gets query for [[AuthAssignments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::class, ['item_name' => 'name']);
    }

    /**
     * Gets query for [[Children]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * Gets query for [[Parents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }


    /**
     * @return string[]
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_ACTIVE => 'Активна',
            self::STATUS_INACTIVE => 'Аннулирована',
        ];
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }

    /**
     * @return array
     */
    public function getPermissionSelect()
    {
        $selected = ArrayHelper::getColumn($this->getChildren()->select('name')->asArray()->all(), 'name');

        return ArrayHelper::map($this::find()->where(['type' => 2])->andWhere(['not in', 'name', $selected])->all(), 'name', 'description');
    }
}
