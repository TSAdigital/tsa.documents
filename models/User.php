<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property string $roles
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $new_password
 * @property string $password write-only password
 * @property Employee $employee
 * @property AuthAssignment $userRoles
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $new_password;
    public $roles;

    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const SCENARIO_USER_CREATE = 'userCreate';
    const SCENARIO_USER_UPDATE = 'userUpdate';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'match','pattern' => '/^([a-zA-Z]+)$/', 'message' => 'Значение «{attribute}» должен содержать латинские символы'],
            ['username', 'string', 'min' => 3],
            ['username', 'unique'],
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

            ['new_password', 'required', 'on' => self::SCENARIO_USER_CREATE],
            ['new_password', 'string', 'min' => 6],
            //['new_password', 'match','pattern' => '/^([a-zA-Z0-9]+)$/', 'message' => 'Значение «{attribute}» должен содержать цифры или латинские символы'],
            //['new_password', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['new_password', 'trim'],

            ['email', 'email'],
            ['email', 'unique'],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

            ['employee_id', 'integer'],
            ['employee_id', 'unique', 'message' => '«{attribute}» уже используется другим пользователем'],
            ['employee_id', 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['employee_id' => 'id']],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['status', 'default', 'value'=> self::STATUS_ACTIVE],
            ['status', 'required'],

            ['roles', 'safe'],
            ['roles', 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_USER_CREATE] = ['username', 'email', 'status', 'employee_id', 'new_password', 'roles'];
        $scenarios[self::SCENARIO_USER_UPDATE] = ['username', 'email', 'status', 'employee_id', 'new_password', 'roles'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор',
            'username' => 'Имя пользователя',
            'new_password' => 'Пароль',
            'employee_id' => 'Сотрудник',
            'employee_name' => 'Сотрудник',
            'roles' => 'Роль',
            'status' => 'Статус',
            'created_at' => 'Запись создана',
            'updated_at' => 'Запись обновлена',
        ];
    }

    /**
     *
     */
    public static function getRolesDropdown()
    {
        return ArrayHelper::map(AuthItem::find()->where(['type' => 1])->all(), 'name', 'description');
    }

    /**
     *
     */
    public function saveRoles()
    {
        if(!empty($this->roles)){
            Yii::$app->authManager->revokeAll($this->getId());
            if($role = Yii::$app->authManager->getRole($this->roles)){
                Yii::$app->authManager->assign($role, $this->getId());
            }
        }
    }

    /**
     *
     */
    public function getUserRoles()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     *
     */
    public function afterFind()
    {
        $this->roles = !empty($this->userRoles->item_name) ? $this->userRoles->item_name : null;
    }

    /**
     *
     */
    public function getRolesName()
    {
        return ArrayHelper::getValue(self::getRolesDropdown(), $this->roles);
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
     * Gets query for [[Employee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['id' => 'employee_id']);
    }

    /**
     *
     */
    public function getEmployee_name()
    {
        return implode(' ', [
            $this->employee->last_name ?? $this->username,
            $this->employee->first_name ?? null,
            $this->employee->middle_name ?? null
        ]);
    }

    /**
     *
     */
    public function getEmployeeNamePosition()
    {
        return implode(' ', [
            $this->employee->last_name ?? $this->username,
            $this->employee->first_name ?? null,
            $this->employee->middle_name ?? null,
            isset($this->employee->position->name) ? sprintf('(%s)', $this->employee->position->name) : null
        ]);
    }


    /**
     *
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->saveRoles();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     *
     */
    public function beforeSave($insert)
    {
        !$this->isNewRecord ?: $this->generateAuthKey();
        empty($this->new_password) ?: $this->setPassword($this->new_password);
        return parent::beforeSave($insert);
    }

    /**
     *
     */
    public function getAllUser()
    {
        return ArrayHelper::map(self::findAll(['status' => self::STATUS_ACTIVE]),'id','employee_name');
    }

}
