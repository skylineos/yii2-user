<?php

namespace skyline\yii\user\models\forms;

use skyline\yii\user\models\forms\CommonFormModel;
use skyline\yii\user\models\Email;

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
            $appParams = \Yii::$app->params;

            $email = new Email();
            $email->toEmail = $emailAddress;
            $email->subject = \Yii::$app->getModule('user')->passwordRecoverySubject;
            $email->template = '@vendor/skylineos/yii.user/mail/password-recovery';
            $email->params = [
                'logo' => array_key_exists('logoPath', $appParams) ? \Yii::getAlias('@app') . $appParams['logoPath'] : '',
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
