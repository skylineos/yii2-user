<?php

namespace app\modules\user\models\forms;

use Yii;
use yii\base\Model;
use app\modules\user\models\User;

/**
 * RequestPasswordReset is the model behind the password recovery reset form.
 *
 */
class RequestPasswordReset extends Model
{
    public $email;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @param string email the email to whom we need to send an email
     * @return bool whether an email was sent
     */
    public function sendPasswordRecoveryEmail($email = null, $token = null)
    {
        if ($email !== null && $token !== null) {
            // Load the module so we can access necessary parameters
            $userModule = \Yii::$app->getModule('user');

            // TODO: build this with a proper template and content
            $message = Yii::$app->mailer->compose('@app/modules/user/mail/password-recovery', [
                'logo' => \Yii::getAlias('@app').'/web/static/media/logo.svg',
                'token' => $token,
                'email' => $email,
                ])
                ->setFrom([$userModule->supportEmail => $userModule->supportEmailDisplayName])
                ->setTo($email)
                ->setSubject($userModule->passwordRecoverySubject);

            $message->send();

            return true;
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->username);
        }

        return $this->_user;
    }
}
