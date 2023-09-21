<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\bootstrap4\Html;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "group".
 *
 * @property int $id
 * @property string $name
 * @property string $user
 * @property int $user_id
 * @property int $visibility
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Group extends ActiveRecord
{
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const VISIBILITY_PUBLIC = 1;
    const VISIBILITY_PRIVATE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'group';
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
            ['name', 'string', 'max' => 255],
            ['name', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['name', 'trim'],
            ['name', 'required'],

            ['user', 'each', 'rule' => ['integer']],
            ['user', 'required'],

            ['user_id', 'integer'],
            ['user_id', 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            ['user_id', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['user_id', 'required'],

            ['visibility', 'in', 'range' => [self::VISIBILITY_PUBLIC, self::VISIBILITY_PRIVATE]],
            ['visibility', 'default', 'value'=> self::VISIBILITY_PRIVATE],

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
            'id' => 'Идентификатор',
            'name' => 'Наименование',
            'user' => 'Сотрудники',
            'visibility' => 'Публичная группа (будет отображаться у всех пользователей)',
            'status' => 'Статус',
            'created_at' => 'Запись создана',
            'updated_at' => 'Запись обновлена',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
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
     * @param $id
     * @return string
     */
    public function getUsers($id)
    {
        return implode(' &equiv; ', ArrayHelper::map(User::findAll(['id' => $id]),'id', function($data){return  Html::a($data->employee_name, ['site/profile', 'id' => $data->id]);}));
    }
}
