<?php

namespace app\modules\user\models\forms;

use Yii;
use yii\base\Model;

/**
 * RequestPasswordReset is the model behind the password recovery reset form.
 *
 */
class ResetPassword extends Model
{
    public $password;
    public $verifyPassword;
    public $email;
    public $token;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email'], 'email'],
            [['token'], 'string'],
            [['password', 'verifyPassword', 'email', 'token'], 'required'],
            [['password', 'verifyPassword'], 'trim'],
            [['password'], 'string', 'length' => [8, 255]],
            ['password', 'compare', 'compareAttribute' => 'verifyPassword'],
        ];
    }
}
