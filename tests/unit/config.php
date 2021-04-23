<?php

use Codeception\Stub\Expected;
use yii\web\Application;
use yii\db\ColumnSchema;

$mailer = null;
return [
    'id' => 'foo',
    'basePath' => '/',
    'aliases' => [
        '@mail' => __DIR__ . '/../../mail',
    ],
    'components' => [
        'db' => [
            'class' => 'skyline\tests\mocks\Mocker',
            'originalClass' => 'yii\db\Connection',
            'mocks' => [
                'open' => '',
                'close' => '',
                'getSchema' => [
                    'class' => 'skyline\tests\mocks\Mocker',
                    'originalClass' => 'yii\db\mysql\Schema',
                    'mocks' => [
                        'getTableSchema' => [
                            'class' => 'skyline\tests\mocks\Mocker',
                            'originalClass' => 'yii\db\TableSchema',
                            'originalClassArgs' => [
                                'columns' => [
                                    'id' => new ColumnSchema(
                                        [
                                            'dbType' => 'int',
                                            'isPrimaryKey' => true,
                                            'name' => 'id'
                                        ]
                                    ),
                                    'passwordResetToken' => new ColumnSchema([
                                        'dbType' => 'string',
                                        'name' => 'passwordResetToken',
                                    ]),
                                    'passwordResetTokenExp' => new ColumnSchema([
                                        'dbType' => 'string',
                                        'name' => 'passwordResetTokenExp',
                                    ]),
                                ],
                            ],
                        ]
                    ]
                ],
            ],
        ],
    ],
    'params' => [
        'supportEmail' => 'foo@bar.com',
        'supportEmailDisplayName' => 'Foo Bar',
        'logoSrc' => './logo.png'
    ]
];
