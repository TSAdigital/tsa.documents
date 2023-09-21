<?php

namespace app\models;

use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap4\Html;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $uniq_id
 * @property string $name
 * @property string|null $description
 * @property string $date
 * @property int|null $executor_id
 * @property string|null $resolution
 * @property int $priority
 * @property int $user_id
 * @property int $discussion
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property User $executor
 */
class Task extends ActiveRecord
{
    public $date_from;
    public $date_to;

    const STATUS_DRAFT = 8;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const DISCUSSION_DISABLE = 0;
    const DISCUSSION_ENABLE = 1;

    const PRIORITY_LOW = 8;
    const PRIORITY_MIDDLE = 9;
    const PRIORITY_HIGH = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
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
            ['name', 'string', 'max' => 255],
            ['name', 'trim'],
            ['name', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['name', 'required'],

            ['description', 'string', 'max' => 1000],
            ['description', 'trim'],
            ['description', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

            ['date', 'safe'],
            ['date', 'required'],

            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],

            ['executor_id', 'integer'],
            ['executor_id', 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
            ['executor_id', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['executor_id', 'default', 'value' => NULL],

            ['resolution', 'each', 'rule' => ['integer']],
            ['resolution', 'default', 'value' => NULL],

            ['priority', 'in', 'range' => [self::PRIORITY_LOW, self::PRIORITY_MIDDLE, self::PRIORITY_HIGH]],
            ['priority', 'required'],

            ['user_id', 'integer'],
            ['user_id', 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            ['user_id', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['user_id', 'required'],

            ['uniq_id', 'string'],
            ['uniq_id', 'required'],

            ['discussion', 'in', 'range' => [self::DISCUSSION_ENABLE, self::DISCUSSION_DISABLE]],
            ['discussion', 'default', 'value'=> self::DISCUSSION_ENABLE],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DRAFT]],
            ['status', 'default', 'value'=> self::STATUS_DRAFT],
            ['status', 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uniq_id' => 'Идентификатор',
            'name' => 'Наименование',
            'description' => 'Описание',
            'date' => 'Дата и время',
            'executor_id' => 'Куратор',
            'resolution' => 'Резолюция',
            'priority' => 'Приоритет',
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
            self::STATUS_ACTIVE => 'Активна',
            self::STATUS_INACTIVE => 'Аннулирована',
            self::STATUS_DRAFT => 'Черновик',
        ];
    }

    /**
     * @return array
     */
    public static function getPrioritysArray()
    {
        return [
            self::PRIORITY_LOW => 'Низкий',
            self::PRIORITY_MIDDLE => 'Средний',
            self::PRIORITY_HIGH => 'Высокий',
        ];
    }

    /**
     * @param $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->date = !empty($this->date) ? strtotime($this->date) : NULL;

        return parent::beforeSave($insert);
    }

    /**
     * @return void
     * @throws InvalidConfigException
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->date = !empty($this->date) ? Yii::$app->formatter->asDatetime($this->date, 'short') : NULL;
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
     * @return mixed
     * @throws Exception
     */
    public function getPriorityName()
    {
        return ArrayHelper::getValue(self::getPrioritysArray(), $this->priority);
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
