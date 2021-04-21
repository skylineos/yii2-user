<?php

use Codeception\Stub\Expected;
use yii\web\Application;

$mailer = null;
return [
    'id' => 'foo',
    'basePath' => '/',
    'aliases' => [
        '@mail' => __DIR__ . '/../../mail',
        '@sqlite' => __DIR__ . '../data'
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlite:' . __DIR__ . '/data/db.sqlite',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
    'params' => [
        'supportEmail' => 'foo@bar.com',
        'supportEmailDisplayName' => 'Foo Bar',
        'logoSrc' => './logo.png'
    ]
];
