<?php

namespace app\modules\user\models\forms;

use app\modules\user\models\forms\CommonFormModel;
use app\modules\user\models\Email;

/**
 * RequestPasswordReset is the model behind the password recovery reset form.
 *
 */
class RequestPasswordReset extends CommonFormModel
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
     * sendPasswordRecoveryEmail Sends a password recovery email
     *
     * @param string emailAddress the email to whom we need to send an email
     * @return bool whether an email was sent
     */
    public function sendPasswordRecoveryEmail($emailAddress = null, $token = null)
    {
        if ($emailAddress !== null && $token !== null) {
            $email = new Email();
            $email->toEmail = $emailAddress;
            $email->subject = \Yii::$app->getModule('user')->passwordRecoverySubject;
            $email->template = '@app/modules/user/mail/password-recovery';
            $email->params = [
                'logo' => \Yii::getAlias('@app').'/web/static/media/logo.svg',
                'token' => $token,
                'email' => $emailAddress,
            ];

            if ($email->sendEmail()) {
                return true;
            }
        }

        return false;
    }
}
