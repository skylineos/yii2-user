<?php

return [
    'id' => 'Yii2 User Module',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'homeUrl' => '/cms',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Eiz_bnJClPYQc_zO8McmbvqVv_QOLd2J',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'skyline\yii\user\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/cms/user/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'fileTransportPath' => '@runtime/mail',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'assetManager' => [
            'basePath' => dirname(__DIR__) . '/tests/_data',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/cms/user/login' => '/user/user/login',
                '/cms/user/logout' => '/user/user/logout',
                '/cms' => '/cms',
                '/cms/user/<action>' => '/user/user/<action>',
                '/cms/user/' => '/user/user',
                '/cms/<controller>' => '/cms/<controller>',
                '/cms/<controller>/<action>' => '/cms/<controller>/<action>',
                '/' => '/',
                '<slug:.*>' => '/site/render',
            ],
        ],
    ],
    'modules' => [
        'metronic' => [ // metronic
            'class' => 'skyline\yii\metronic\Module',
        ],
        'cms' => [
            'class' => 'skyline\yii\cms\Module',
            'userBaseUrl' => '/cms/user',
            'frontendAssetsClassname' => 'app\assets\CommonAssets',
        ],
        'user' => [
            // Required Parameters
            'class' => 'skyline\yii\user\Module',
            'defaultRoute' => 'UserController',

            /**
             * Optional settings
             */

            // Where to redirect after logging in/handling auth sessions
            'homeRedirect' => '/cms',

            // How long the password reset token should last (eg. today + x time)
            // @see https://www.php.net/strtotime
            'passwordResetTokenExp' => '+5 days',

            // How long the remember me should function
            'rememberMeExpiration' => 3600 * 24 * 30,

            'layout' => '@app/views/layouts/login',

        ],
    ],
    'params' => [
        'debug' => true,
        'supportEmail' => 'csg.support@skylinenet.net',
        'supportEmailDisplayName' => 'Stream Tream & the Jacks',
        'newUserEmailSubject' => 'New User Email Subject',
        'passwordRecoverySubject' => 'Password Recovery Subject',
    ]
];
