<?php

namespace app\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sign_file".
 *
 * @property int $id
 * @property string $sign
 * @property int $file_id
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Upload $file
 * @property User $user
 */
class SignFile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sign_file';
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
            ['sign', 'required'],
            ['sign', 'string'],

            ['file_id', 'required'],
            ['file_id', 'integer'],
            ['file_id', 'exist', 'skipOnError' => true, 'targetClass' => Upload::class, 'targetAttribute' => ['file_id' => 'id']],
            ['file_id', 'unique', 'targetAttribute' => ['file_id', 'user_id']],

            ['user_id', 'required'],
            ['user_id', 'integer'],
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
            'sign' => 'Подпись',
            'file_id' => 'Файл',
            'user_id' => 'Пользователь',
            'created_at' => 'Запись создана',
            'updated_at' => 'Запись обновлена',
        ];
    }

    /**
     * Gets query for [[File]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(Upload::class, ['id' => 'file_id']);
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
