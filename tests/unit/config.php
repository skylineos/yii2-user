<?php

use Codeception\Stub\Expected;
use yii\web\Application;

$mailer = null;
return [
    'id' => 'foo',
    'basePath' => '/',
    'aliases' => [
        '@mail' => __DIR__ . '/../../mail'
    ],
    'params' => [
        'supportEmail' => 'foo@bar.com',
        'supportEmailDisplayName' => 'Foo Bar',
        'logoSrc' => './logo.png'
    ]
];
