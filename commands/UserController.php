<?php

namespace app\modules\user\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\validators\EmailValidator;
use app\modules\user\models\User;

class UserController extends Controller
{
    /**
     * Add new user. Supply an email address and instructions to complete the setup will be sent to the new user
     *
     * @param string $email The email address of the new user account
     * @param string $uri The current uri this script should direct the user to (eg. https://www.google.com)
     * @return integer yii\console\ExitCode
     */
    public function actionAdd(string $email = null) : int
    {
        $emailValidator = new EmailValidator();

        if ($email === null || !$emailValidator->validate($email)) {
            echo "Email not provided or not valid";
            return ExitCode::DATAERR;
        }

        \Yii::$app->db->createCommand()->checkIntegrity(false)->execute();

        $model = new User;
        $model->email = $model->name = $email;

        $security = \Yii::$app->getSecurity();
        $model->passwordHash = $security->generatePasswordHash($security->generateRandomString(16));
        $model->passwordResetToken = $security->generateRandomString(255);
        $model->passwordResetTokenExp = strftime('%F %T', strtotime('+5 day'));

        if ($model->save()) {
            \Yii::$app->db->createCommand()->checkIntegrity(true)->execute();
            echo "The user account has been created with a random password. You should direct the user to 'Forgot Password' in order to finish creating their account.\n";
            return ExitCode::OK;
        }

        \Yii::$app->db->createCommand()->checkIntegrity(true)->execute();
        return ExitCode::UNSPECIFIED_ERROR;
    }

    public function actionDelete(string $email = null) : int
    {
        $emailValidator = new EmailValidator();

        if ($email === null || !$emailValidator->validate($email)) {
            echo "Email not provided or not valid";
            return ExitCode::DATAERR;
        }

        $user = User::find()->where(['email' => $email])->one();
        if ($user) {
            $user->delete();
            echo "User Deleted\n";
            return ExitCode::OK;
        }

        echo "Could not find user to delete\n";
        return ExitCode::UNSPECIFIED_ERROR;
    }
}
