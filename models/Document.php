<?php

namespace app\models;

use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap4\Html;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "certificate".
 *
 * @property int $id
 * @property string $uniq_id
 * @property string $name
 * @property string $number
 * @property string $date
 * @property string $validity_period
 * @property Json $resolution
 * @property int|null $executor_id
 * @property int $user_id
 * @property int $type
 * @property int $discussion
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property User $user
 * @property DocumentType $type0
 */
class Document extends ActiveRecord
{
    public $date_from;
    public $date_to;

    const STATUS_DRAFT = 8;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const DISCUSSION_DISABLE = 0;
    const DISCUSSION_ENABLE = 1;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'document';
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
     * @return array
     */
    public function rules()
    {
        return [
            ['number', 'string', 'max' => 255],
            ['number', 'trim'],
            ['number', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['number', 'required'],

            ['name', 'string', 'max' => 255],
            ['name', 'trim'],
            ['name', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['name', 'required'],

            ['date', 'date'],
            ['date', 'required'],

            ['validity_period', 'date'],
            ['validity_period', 'checkValidityPeriod'],

            ['uniq_id', 'string'],
            ['uniq_id', 'required'],

            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],

            ['executor_id', 'integer'],
            ['executor_id', 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
            ['executor_id', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['executor_id', 'default', 'value' => NULL],

            ['resolution', 'each', 'rule' => ['integer']],
            ['resolution', 'default', 'value'=> NULL],

            ['user_id', 'integer'],
            ['user_id', 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            ['user_id', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['user_id', 'required'],

            ['type', 'integer'],
            ['type', 'exist', 'skipOnError' => true, 'targetClass' => DocumentType::class, 'targetAttribute' => ['type' => 'id']],
            ['type', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['type', 'required'],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DRAFT]],
            ['status', 'default', 'value'=> self::STATUS_DRAFT],
            ['status', 'required'],

            ['discussion', 'in', 'range' => [self::DISCUSSION_ENABLE, self::DISCUSSION_DISABLE]],
            ['discussion', 'default', 'value'=> self::DISCUSSION_ENABLE],
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uniq_id' => 'Идентификатор',
            'name' => 'Наименование',
            'number' => 'Номер',
            'date' => 'Дата',
            'validity_period' => 'Действует до',
            'type' => 'Тип',
            'executor_id' => 'Куратор',
            'resolution' => 'Резолюция',
            'user_id' => 'Автор',
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
     * @return ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(DocumentType::class, ['id' => 'type']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * @return array
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_INACTIVE => 'Аннулирован',
            self::STATUS_DRAFT => 'Черновик',
        ];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }

    /**
     * @param $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->date = !empty($this->date) ? date('Y-m-d', strtotime($this->date)) : NULL;
        $this->validity_period = !empty($this->validity_period) ? date('Y-m-d', strtotime($this->validity_period)) : NULL;

        return parent::beforeSave($insert);
    }

    /**
     * @return void
     * @throws InvalidConfigException
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->date = !empty($this->date) ? Yii::$app->formatter->asDate($this->date) : NULL;
        $this->validity_period = !empty($this->validity_period) ? Yii::$app->formatter->asDate($this->validity_period) : NULL;
    }


    /**
     * @param $id
     * @return string
     */
    public function getUsers($id)
    {
        return implode(' &equiv; ', ArrayHelper::map(User::findAll(['id' => $id]),'id', function($data){return  Html::a($data->employee_name, ['site/profile', 'id' => $data->id]);}));
    }

    /**
     * @param $attribute
     * @return void
     */
    public function checkValidityPeriod($attribute) {
        if ($this->validity_period) {
            if(strtotime($this->validity_period) < strtotime($this->date)){
                $this->addError($attribute, 'Дата действия документа не должна быть меньше даты документа.');
            }
        }
    }

}