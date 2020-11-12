<?php

namespace app\modules\user\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use Aws\Ses\SesClient;
use yii\validators\EmailValidator;
use app\modules\user\models\User;

class UserController extends Controller
{
    /**
     * Add new user. Supply an email address and instructions to complete the setup will be sent to the new user
     *
     * @param string $email
     * @return integer yii\console\ExitCode
     */
    public function actionAdd(string $email = null) : int
    {
        $emailValidator = new EmailValidator();

        if ($email === null || !$emailValidator->validate($email)) {
            echo "Email not provided or not valid";
            return ExitCode::DATAERR;
        }

        $sesClient = new SesClient([
            'profile' => \Yii::$app->params['sesClient']['profile'],
            'version' => \Yii::$app->params['sesClient']['version'],
            'region'  => \Yii::$app->params['sesClient']['region'],
        ]);

        /**
         * Send the user an email directing them to reset their password and login
         */
        $model = new User();
        $model->name = $model->email = $email;

        /**
         * Set Blank Password
         */
        $security = \Yii::$app->getSecurity();
        $model->passwordHash = $security->generatePasswordHash($security->generateRandomString(16));
        $model->passwordResetToken = $security->generateRandomString(255);
        $model->passwordResetTokenExp = strftime('%F %T', strtotime('+5 day'));

        if ($model->save(false)) {
            $view = new \yii\web\View;

            $sesClient->sendEmail([
                'Destination' => [
                    'ToAddresses' => [$model->email],
                ],
                'ReplyToAddresses' => [\Yii::$app->params['supportEmail']],
                'Source' => \Yii::$app->params['supportEmail'],
                'Message' => [
                    'Body' => [
                        'Html' => [
                            'Charset' => 'UTF-8',
                            'Data' => $view->render('@app/modules/user/mail/create-account-html', [
                                'model' => $model,
                            ]),
                        ],
                        'Text' => [
                            'Charset' => 'UTF-8',
                            'Data' => $view->render('@app/modules/user/mail/create-account-text', [
                                'model' => $model,
                            ]),
                        ],
                    ],
                    'Subject' => [
                        'Charset' => 'UTF-8',
                        'Data' => \Yii::$app->params['newUserEmailSubject'],
                    ],
                ],
            ]);

            return ExitCode::OK;
        }

        return ExitCode::UNSPECIFIED_ERROR;
    }
}
