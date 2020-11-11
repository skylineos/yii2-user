### Installation

1. Clone this module to @app/module/User
2. Create the User table `php yii migrate/up --migrationPath=@app/modules/user/migrations`
3. Configure params in `config/params.php`
4. Tell yii to use this module for user management
    1. edit `config/web.php` ,find and change (or add) the `user` block in `components` as follows
    ```
    'components' => [
        ...*** ADD THIS PART BELOW ***
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'enableAutoLogin' => true,
        ],
        ...
    ]
    ```
5. Add this module to the bootstrap for easy routing/reference
    1. edit `config/web.php`, find the `modules` block (or add it if it doesn't exist) and add the following:
    ```
    'components' => [
        ...
    ],
    'modules' => [
        ...*** ADD THIS PART BELOW ***
        'user' => [
            'class' => 'app\modules\user\Module',
            'defaultRoute' => 'UserController',
        ],
        ...
    ]

### Params

* `supportEmail`
* `supportEmailDisplayName`
* `passwordRecoverySubject`
* `newUserEmailSubject`
* `sesClient['profile'] = 'default'`
* `sesClient['version'] = 'latest'`
* `sesClient['region'] = 'us-east-1'`

### Migrations

`php yii migrate/up --migrationPath=@app/modules/user/migrations`

### Dependencies

The following are required for this module to work

* `composer require aws/aws-sdk-php`
    * SES configured @ AWS
* `https://gitlab.skyts.io/csg/yii2.module.metronic`