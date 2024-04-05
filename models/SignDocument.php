<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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
class SignDocument extends \yii\db\ActiveRecord
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
            [['sign', 'document_id', 'user_id'], 'required'],
            [['sign'], 'string'],
            [['document_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::class, 'targetAttribute' => ['document_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            ['document_id', 'unique', 'targetAttribute' => ['document_id', 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sign' => 'Sign',
            'document_id' => 'Document ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
