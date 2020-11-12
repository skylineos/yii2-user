<?php

namespace app\modules\user\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "User".
 *
 * @property integer $id
 * @property string $authKey
 * @property string $passwordResetToken
 * @property string $lastLogin
 * @property string $email
 * @property string $passwordHash
 * @property string $name
 * @property integer $status
 * @property string $dateCreated
 * @property string $lastModified
 * @property integer $modifiedBy
 * @property string $passwordResetTokenExp
 *
 * @property Agency $agency
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const PASSWORD_RESET_TOKEN_EXPIRATION = 3600; // 1 hour

    /**
     * There is no property for password of verifyPassword, only passwordHash.
     *
     * @var string
     */
    public string $password;

    /**
     * There is no property for password of verifyPassword, only passwordHash.
     *
     * @var string
     */
    public string $verifyPassword;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'User';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => ['dateCreated', 'lastModified'],
                'updatedAtAttribute' => ['lastModified'],
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'modifiedBy',
                'updatedByAttribute' => 'modifiedBy',
                'defaultValue' => 1,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        /**
         * passwordHash is deemed safe as it will be explicitly set in the various methods and should never
         * need to be required by independent actions (eg. update).
         */
        return [
            [['lastLogin', 'passwordHash', 'passwordResetTokenExp'], 'safe'],
            [['name', 'email'], 'required'],
            [['status'], 'integer'],
            [['authKey'], 'string', 'max' => 32],
            [['passwordResetToken', 'passwordHash'], 'string', 'max' => 255],
            [['email', 'name', 'role'], 'string', 'max' => 100],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['password'], 'trim'],
            [
                [
                    'password',
                    'verifyPassword'
                ],
                'string',
                'length' => [8, 255],
                'when' => function ($model) {
                    return isset($password);
                }
            ],
            ['password', 'compare', 'compareAttribute' => 'verifyPassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'authKey' => 'Auth Key',
            'passwordResetToken' => 'Password Reset Token',
            'lastLogin' => 'Last Login',
            'email' => 'Email',
            'passwordHash' => 'Password Hash',
            'name' => 'Name',
            'status' => 'Status',
            'dateCreated' => 'Date Created',
            'lastModified' => 'Last Modified',
            'modifiedBy' => 'Modified By',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new \yii\base\NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return User::findOne([
            'email' => $email,
            'status' => self::STATUS_ACTIVE
            ]);
    }

    /**
     * Finds a user for password reset with given email/token (where token is not expired)
     *
     * @param [type] $email
     * @param [type] $token
     * @return static|null
     */
    public static function findByToken(string $email, string $token)
    {
        $date = new \DateTime('-24 Hours', new \DateTimeZone('UTC'));
        return User::find()
                ->where(['email' => $email, 'passwordResetToken' => $token])
                ->andWhere(['>=', 'passwordResetTokenExp', $date->format('Y-m-d H:i:s')])
                ->one();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->passwordHash);
    }

    /**
     * Alias for email as some packages require a 'user' property (rbac)
     *
     * @return string
     */
    public function getUsername() : string
    {
        return $this->email;
    }
}
