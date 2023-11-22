<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap4\Html;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $start
 * @property int|null $end
 * @property string|null $color
 * @property string|null $resolution
 * @property int $user_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */
class Event extends ActiveRecord
{
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const COLOR_BLUE = '#007bff';
    const COLOR_RED = '#dc3545';
    const COLOR_GREEN = '#28a745';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event';
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

            ['description', 'string'],
            ['description', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['description', 'trim'],

            ['start', 'safe'],
            ['start', 'required'],

            ['end', 'safe'],

            ['color', 'in', 'range' => [self::COLOR_GREEN, self::COLOR_BLUE, self::COLOR_RED]],

            ['user_id', 'integer'],
            ['user_id', 'required'],
            ['user_id', 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],

            ['resolution', 'safe'],
            ['resolution', 'default', 'value'=> NULL],

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
            'description' => 'Описание',
            'start' => 'Начало',
            'end' => 'Завершение',
            'color' => 'Цвет',
            'resolution' => 'Резолюция',
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
     * @return string[]
     */
    public static function getColorsArray()
    {
        return [
            self::COLOR_BLUE => 'Синий',
            self::COLOR_RED => 'Красный',
            self::COLOR_GREEN => 'Зеленый',
        ];
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getColorName()
    {
        return ArrayHelper::getValue(self::getColorsArray(), $this->color);
    }

    /**
     * @param $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->start = !empty($this->start) ? strtotime($this->start) : NULL;
        $this->end = !empty($this->end) ? strtotime($this->end) : NULL;

        return parent::beforeSave($insert);
    }

    /**
     * @return void
     * @throws InvalidConfigException
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->start = !empty($this->start) ? Yii::$app->formatter->asDatetime($this->start, 'short') : NULL;
        $this->end = !empty($this->end) ? Yii::$app->formatter->asDatetime($this->end, 'short') : NULL;
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
