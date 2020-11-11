### Installation

1. Clone this module to @app/module/User
2. Create the User table `php yii migrate/up --migrationPath=@app/module/user/migrations`
3. Configure params in `config/params.php`

### Params

* `supportEmail`
* `supportEmailDisplayName`
* `passwordRecoverySubject`
* `newUserEmailSubject`
* `sesClient['profile'] = 'default'`
* `sesClient['version'] = 'latest'`
* `sesClient['region'] = 'us-east-1'`

### Migrations

`php yii migrate/up --migrationPath=@app/module/user/migrations`

### Dependencies

The following are required for this module to work

* `composer require aws/aws-sdk-php`
    * SES configured @ AWS
* `https://gitlab.skyts.io/csg/yii2.module.metronic`