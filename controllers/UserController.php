<?php

namespace skyline\yii\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use skyline\yii\user\models\User;
use skyline\yii\user\models\forms\LoginForm;
use skyline\yii\user\models\search\UserSearch;
use skyline\yii\user\models\Email;

/**
 * UserController implements the CRUD  and authentication (login/logout/reset/etc) actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     * @see https://www.yiiframework.com/doc/guide/2.0/en/security-authorization
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // Must be authenticated
                    [
                        'actions' => [
                            'logout',
                            'index',
                            'create',
                            'update',
                            'delete',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // Must be guest
                    [
                        'actions' => [
                            'login',
                            'password-reset-request',
                            'change-password',
                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect([\Yii::$app->controller->module->homeRedirect]);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        // Do not render the page with the user's previous password attempt. #security
        $model->password = '';

        return $this->render('@app/modules/user/views/user/security/login', [
            'model' => $model,
            'forgotPasswordModel' => new \skyline\yii\user\models\forms\RequestPasswordReset(),
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
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
            // Load the module so we can access necessary parameters
            $userModule = \Yii::$app->controller->module;

            /**
             * Create a random password
             */
            $security = \Yii::$app->getSecurity();
            $model->passwordHash = $security->generatePasswordHash($security->generateRandomString(16));
            $model->setResetToken(true);

            if ($model->save()) {
                $email = new Email();
                $email->toEmail = $model->email;
                $email->subject = $userModule->newUserEmailSubject;
                $email->template = '@app/modules/user/mail/create-account-html';
                $email->params = [
                    'logoSrc' => \Yii::getAlias('@app') . '/web/static/media/logo.svg',
                    'model' => $model,
                ];

                if ($email->sendEmail()) {
                    \Yii::$app->session->setFlash('success', 'User Created');
                } else {
                    \Yii::$app->session->setFlash('warning', 'The user was created successfully, but the email could
                    not be sent to the user.');
                }

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
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'User Updated');
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
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = User::STATUS_INACTIVE;

        if ($model->save()) {
            \Yii::$app->session->setFlash('danger', 'User Deleted');
        } else {
            \Yii::$app->session->setFlash('danger', 'Could not delete user, please contact support');
        }

        return $this->redirect(['index']);
    }

    /**
     * Handles the password reset request; sets a new reset token and sends the recovery password to the
     * requesting account. The forgot password inteface is rendered in self::actionLogin.
     * A contextual flash message will be set and the user redirected to the login page
     *
     * @return mixed
     */
    public function actionPasswordResetRequest()
    {
        $model = new \skyline\yii\user\models\forms\RequestPasswordReset();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::find()
                ->where(['email' => Yii::$app->request->post('RequestPasswordReset')['email'] ])
                ->one();

            if (is_object($user)) {
                $user->setResetToken(true);

                if ($user->save()) {
                    $model->sendPasswordRecoveryEmail($user->email, $user->passwordResetToken);
                    \Yii::$app->session->setFlash(
                        'recoverySent',
                        'An email has been sent to your email address with details regarding how you may recover
                         your password'
                    );

                    return $this->redirect([\Yii::$app->controller->module->homeRedirect]);
                }
            }
        }

        Yii::$app->session->setFlash(
            'accountNotFound',
            'There was an issue processing your request. Please contact support.'
        );

        return $this->redirect(['/user/user/login']);
    }

    /**
     * After a user is created via the cms, they are sent an email with instructions to complete their
     * registration (set their password). Those instructions direct the user to this action
     *
     * -- or --
     *
     * After a user successfully submits a 'reset-password' request, the email they receive will direct them
     * here.
     *
     * This renders the 'reset-password' form and handles the subsequent post
     *
     * To complete the form, post must validate according to \skyline\yii\user\models\forms\ResetPassword()
     * To start the process at all, get['email'] and get['token'] must be procided and a user matching that
     * pair must be found (the token is also compared for age @see skyline\yii\user\models\User::passwordResetTokenExp)
     *
     * @return response
     */
    public function actionChangePassword()
    {
        $request = \Yii::$app->request;

        $model = new \skyline\yii\user\models\forms\ResetPassword();

        /**
         * Handle the request after the password is given and the form is submitted
         */
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::findByToken($model->email, $model->token);

            if (is_object($user) && isset($user->id)) {
                $user->passwordHash = \Yii::$app->getSecurity()->generatePasswordHash($model->password);
                $user->verifyPassword = $user->passwordHash;
                $user->setResetToken();

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

                return $this->redirect([\Yii::$app->controller->module->homeRedirect]);
            }
        }

        $user = User::findByToken($request->get('email'), $request->get('token'));

        /**
         * Make sure the expected parameters exist and are at least
         */
        if ($request->get('email') === null || !$request->get('token') === null || $user === null) {
            \Yii::$app->session->setFlash(
                'resetFailed',
                'We were unable to reset your password. Please contact support.'
            );
            return $this->redirect([\Yii::$app->controller->module->homeRedirect]);
        }

        $model->email = $request->get('email');
        $model->token = $request->get('token');

        return $this->render('@app/modules/user/views/user/recovery/reset', [
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
    protected function findModel(int $id)
    {
        $model = User::findOne($id);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
