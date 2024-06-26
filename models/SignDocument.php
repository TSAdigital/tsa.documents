<?php

namespace app\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sign_document".
 *
 * @property int $id
 * @property string $sign
 * @property int $document_id
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Document $document
 * @property User $user
 */
class SignDocument extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sign_document';
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

            ['document_id', 'integer'],
            ['document_id', 'required'],
            ['document_id', 'exist', 'skipOnError' => true, 'targetClass' => Document::class, 'targetAttribute' => ['document_id' => 'id']],
            ['document_id', 'unique', 'targetAttribute' => ['document_id', 'user_id']],

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
            'sign' => 'Подпись',
            'document_id' => 'Документ',
            'user_id' => 'Пользователь',
            'created_at' => 'Запись создана',
            'updated_at' => 'Запись обновлена',
        ];
    }

    /**
     * Gets query for [[Document]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::class, ['id' => 'document_id']);
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
