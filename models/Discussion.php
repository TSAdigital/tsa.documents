<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "discussion".
 *
 * @property int $id
 * @property string $message
 * @property string $type
 * @property int $record_id
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */

class Discussion extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'discussion';
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
            ['message', 'string'],
            ['message', 'string', 'max' => 500],
            ['message', 'required'],
            ['message', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['message', 'trim'],

            ['record_id', 'integer'],
            ['record_id', 'required'],

            ['type', 'string', 'max' => 255],
            ['type', 'required'],

            ['user_id', 'integer'],
            ['user_id', 'required'],
            ['user_id', 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор',
            'message' => 'Сообщение',
            'type' => 'Тип',
            'record_id' => 'Запись',
            'user_id' => 'Пользователь',
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
}
