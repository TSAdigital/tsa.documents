<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "telegram".
 *
 * @property int $id
 * @property string $telegram
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Telegram extends ActiveRecord
{
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram';
    }

    /**
     * @return array
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
            ['telegram', 'string', 'max' => 255],
            ['telegram', 'trim'],
            ['telegram', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['telegram', 'required'],
            ['telegram', 'unique'],

            ['username', 'string', 'max' => 255],
            ['username', 'trim'],
            ['username', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['username', 'required'],

            ['first_name', 'string', 'max' => 255],
            ['first_name', 'trim'],
            ['first_name', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['first_name', 'required'],

            ['last_name', 'string', 'max' => 255],
            ['last_name', 'trim'],
            ['last_name', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['last_name', 'required'],

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
            'telegram' => 'Телеграм чат',
            'username' => 'Тег',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'status' => 'Статус',
            'created_at' => 'Запись создана',
            'updated_at' => 'Запись обновлена',
        ];
    }
}