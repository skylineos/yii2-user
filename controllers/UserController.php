<?php

namespace app\modules\citizenAdmin\controllers;

use Yii;
use app\modules\user\models\User;
use app\modules\user\models\search\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Aws\Ses\SesClient;
use yii\helpers\ArrayHelper;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    const LAYOUT_LOGIN = 'login';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new User model.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {

            /**
             * Create a random password
             */
            $security = \Yii::$app->getSecurity();
            $model->passwordHash = $security->generatePasswordHash($security->generateRandomString(16));
            $model->passwordResetToken = $security->generateRandomString(255);
            $model->passwordResetTokenExp = strftime('%F %T', strtotime('+5 day'));


            if ($model->save()) {
                /**
                 * Set up SES
                 */
                $sesClient = new SesClient([
                    'profile' => \Yii::$app->params['sesClient']['profile'],
                    'version' => \Yii::$app->params['sesClient']['version'],
                    'region'  => \Yii::$app->params['sesClient']['region'],
                ]);

                /**
                 * Send the user an email directing them to reset their password and login
                 */
                $view = new \yii\web\View;
                $result = $sesClient->sendEmail([
                    'Destination' => [
                        'ToAddresses' => [$model->email],
                    ],
                    'ReplyToAddresses' => [\Yii::$app->params['supportEmail']],
                    'Source' => 'citizen@skylinecsg.com',
                    'Message' => [
                        'Body' => [
                            'Html' => [
                                'Charset' => 'UTF-8',
                                'Data' => $view->render('@app/modules/citizenAdmin/mail/create-account-html', [
                                    'model' => $model,
                                    'logo' => 'images/citizenLogo.png',
                                ]),
                            ],
                            'Text' => [
                                'Charset' => 'UTF-8',
                                'Data' => $view->render('@app/modules/citizenAdmin/mail/create-account-text', [
                                    'model' => $model,
                                    'logo' => 'images/citizenLogo.png',
                                ]),
                            ],
                        ],
                        'Subject' => [
                            'Charset' => 'UTF-8',
                            'Data' => \Yii::$app->params['newUserEmailSubject'],
                        ],
                    ],
                ]);

                \Yii::$app->session->setFlash('success', 'User Created');
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $om = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->passwordHash = (strlen($model->password) > 0)
                ? \Yii::$app->getSecurity()->generatePasswordHash($model->password)
                : $om->passwordHash;

            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        \Yii::$app->session->setFlash('danger', 'User Deleted');
        return $this->redirect(['index']);
    }

    public function actionCompleteRegistration()
    {
        $this->layout = 'login';
        $request = \Yii::$app->request;

        $model = new \app\modules\user\models\forms\ResetPassword();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::findByToken($model->email, $model->token);

            if (is_object($user) && isset($user->id)) {
                $user->passwordHash = \Yii::$app->getSecurity()->generatePasswordHash($model->password);
                $user->verifyPassword = $user->passwordHash;
                $user->passwordResetToken = \Yii::$app->getSecurity()->generateRandomString(50);

                if ($user->save()) {
                    \Yii::$app->session->setFlash(
                        'recoverySent',
                        'Your password has been reset. Please log in to continue.'
                    );
                } else {
                    \Yii::$app->session->setFlash(
                        'resetFailed',
                        'We were unable to reset your password. Please contact support.'
                    );
                }

                return $this->redirect('/');
            }
        }

        $user = User::findByToken($request->get('email'), $request->get('token'));

        if (strlen($request->get('email')) < 1 || strlen($request->get('token')) < 1 || $user === null) {
            \Yii::$app->session->setFlash(
                'resetFailed',
                'We were unable to reset your password. Please contact support.'
            );
            return $this->goHome();
        }

        $model->email = $request->get('email');
        $model->token = $request->get('token');

        return $this->render('security/complete-registration', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * Renders and handels the forgot-password interface
     * A contextual flash message will be set and the user redirected to the login page
     * @param integer $id
     * @return mixed
     */
    public function actionRequest()
    {
        $this->layout = 'login';

        $model = new \app\modules\user\models\forms\RequestPasswordReset();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::find()->where(['email' => Yii::$app->request->post('RequestPasswordReset')['email'] ])->one();

            if (is_object($user) && isset($user->email)) {
                $token = \Yii::$app->getSecurity()->generateRandomString(255);
                $user->passwordResetToken = $token;
                $user->passwordResetTokenExp = date('Y-m-d H:i:s');

                if ($user->save()) {
                    $model->sendPasswordRecoveryEmail($user->email, $token);
                    \Yii::$app->session->setFlash(
                        'recoverySent',
                        'An email has been sent to your email address with details regarding how you may recover
                         your password'
                    );

                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash(
                        'accountNotFound',
                        'There was an issue processing your request. Please contact support.'
                    );
                }
            } else {
                \Yii::$app->session->setFlash(
                    'accountNotFound',
                    'The email address you entered is not associated with any accounts. Please check your entry and 
                    try again. If you are still having difficulties accessing your account, please contact 
                    <a href="mailto:support@skylinenet.net">support@skylinenet.net</a>'
                );
            }
        }

        return $this->render('recovery/request', [
            'model' => $model,
        ]);
    }

    public function actionReset()
    {
        $this->layout = 'login';
        $request = \Yii::$app->request;

        $model = new \app\modules\user\models\forms\ResetPassword();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::findByToken($model->email, $model->token);

            if (is_object($user) && isset($user->id)) {
                $user->passwordHash = \Yii::$app->getSecurity()->generatePasswordHash($model->password);
                $user->verifyPassword = $user->passwordHash;
                $user->passwordResetToken = \Yii::$app->getSecurity()->generateRandomString(50);

                if ($user->save()) {
                    \Yii::$app->session->setFlash(
                        'recoverySent',
                        'Your password has been reset. Please log in to continue.'
                    );
                } else {
                    \Yii::$app->session->setFlash(
                        'resetFailed',
                        'We were unable to reset your password. Please contact support.'
                    );
                }

                return $this->redirect('/');
            }
        }

        $user = User::findByToken($request->get('email'), $request->get('token'));

        if (strlen($request->get('email')) < 1 || strlen($request->get('token')) < 1 || $user === null) {
            \Yii::$app->session->setFlash(
                'resetFailed',
                'We were unable to reset your password. Please contact support.'
            );
            return $this->goHome();
        }

        $model->email = $request->get('email');
        $model->token = $request->get('token');

        return $this->render('recovery/reset', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = User::findOne($id);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
