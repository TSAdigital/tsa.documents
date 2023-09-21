<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "employees".
 *
 * @property int $id
 * @property string $last_name
 * @property string $first_name
 * @property string|null $middle_name
 * @property int $birthdate
 * @property int $position_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property Position $position
 */
class Employee extends ActiveRecord
{

    /**
     *
     */
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee';
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
            ['birthdate', 'date', 'format' => 'php:d.m.Y'],
            ['birthdate', 'required'],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['status', 'default', 'value'=> self::STATUS_ACTIVE],
            ['status', 'required'],

            ['position_id', 'integer'],
            ['position_id', 'required'],
            ['position_id', 'exist', 'skipOnError' => true, 'targetClass' => Position::class, 'targetAttribute' => ['position_id' => 'id']],

            [['last_name', 'first_name', 'middle_name'], 'string', 'max' => 255],
            [['last_name','first_name','middle_name'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['last_name','first_name','middle_name'], 'trim'],
            [['last_name','first_name'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор',
            'last_name' => 'Фамилия',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'birthdate' => 'Дата рождения',
            'position_id' => 'Должность',
            'position_name' => 'Должность',
            'status' => 'Статус',
            'created_at' => 'Запись создана',
            'updated_at' => 'Запись обновлена',
        ];
    }

    /**
     * Gets query for [[Position]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(Position::class, ['id' => 'position_id']);
    }

    /**
     *
     */
    public function getPosition_name()
    {
        return $this->position->name ?? null;
    }

    /**
     *
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_INACTIVE => 'Заблокирован',
        ];
    }

    /**
     *
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }

    /**
     *
     */
    public function getEmployeeFullName()
    {
        return implode(' ', [
            $this->last_name ?? null,
            $this->first_name ?? null,
            $this->middle_name ?? null
        ]);
    }

    /**
     *
     */
    public function beforeSave($insert)
    {
        $this->birthdate = !empty($this->birthdate) ? date('Y-m-d', strtotime($this->birthdate)) : NULL;

        return parent::beforeSave($insert);
    }

    /**
     *
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->birthdate = !empty($this->birthdate) ? Yii::$app->formatter->asDate($this->birthdate): NULL;
    }
}
