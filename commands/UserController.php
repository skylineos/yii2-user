<?php

namespace app\modules\user\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\validators\EmailValidator;
use app\modules\user\models\User;

/**
 * Command line helpers to generate or delete users. These are most useful when setting up a new deployment,
 * local or otherwise.
 *
 * eg. `php yii user/user/[add|delete] <email address>
 */
class UserController extends Controller
{
    /**
     * Add new user. Supply an email address and instructions to complete the setup will be sent to the new user
     *
     * @param string $email The email address of the new user account
     * @return integer constant of yii\console\ExitCode
     */
    public function actionAdd(string $email = null): int
    {
        $emailValidator = new EmailValidator();

        if ($email === null || !$emailValidator->validate($email)) {
            echo "Email not provided or not valid";
            return ExitCode::DATAERR;
        }

        /**
         * In the case that this is the first user, the foriegn key will fail. No harm
         * in disabling integrity checks for this edge case
         * @todo find a better way... honestly
         */
        \Yii::$app->db->createCommand()->checkIntegrity(false)->execute();

        $model = new User();
        $model->email = $model->name = $email;

        /**
         * The user must visit 'forgot password' after creation. Populate the password with a random
         * pass to prevent unwanted access.
         */
        $security = \Yii::$app->getSecurity();
        $model->passwordHash = $security->generatePasswordHash($security->generateRandomString(16));

        if ($model->save()) {
            \Yii::$app->db->createCommand()->checkIntegrity(true)->execute();
            $msg = "The user account has been created with a random password. You should direct the user to
            'Forgot Password' in order to finish creating their account.\n";
            echo $msg;
            return ExitCode::OK;
        }

        \Yii::$app->db->createCommand()->checkIntegrity(true)->execute();
        return ExitCode::UNSPECIFIED_ERROR;
    }

    /**
     * Deletes a user with the given email address
     *
     * @param string $email The email address of the user to be deleted
     * @return integer constant of yii\console\ExitCode
     */
    public function actionDelete(string $email = null): int
    {
        $emailValidator = new EmailValidator();

        if ($email === null || !$emailValidator->validate($email)) {
            echo "Email not provided or not valid";
            return ExitCode::DATAERR;
        }

        $user = User::find()->where(['email' => $email])->one();

        if ($user) {
            if ($user->delete()) {
                ;
                echo "User Deleted\n";
                return ExitCode::OK;
            }
        }

        echo "Could not find user or could not delete user.\n";
        return ExitCode::UNSPECIFIED_ERROR;
    }
}
