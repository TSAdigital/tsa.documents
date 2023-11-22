<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string|null $text
 * @property int $user_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */
class News extends ActiveRecord
{
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
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
            ['title', 'required'],
            ['title', 'string', 'max' => 255],
            ['title', 'trim'],
            ['title', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

            ['text', 'string'],
            ['text', 'trim'],
            ['text', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

            ['user_id', 'integer'],
            ['user_id', 'required'],
            ['user_id', 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],

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
            'title' => 'Тема',
            'text' => 'Текст',
            'user_id' => 'Автор',
            'status' => 'Статус',
            'created_at' => 'Запись создана',
            'updated_at' => 'Запись обновлена',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     *
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_ACTIVE => 'Активна',
            self::STATUS_INACTIVE => 'Аннулирована',
        ];
    }

    /**
     *
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }
}
