# Skyilne Yii User Extension


An extension for the Yii framework to boilerplate the creation, update, viewing, and removal of authentication users.

- [Skyilne Yii User Extension](#skyilne-yii-user-extension)
    - [Installation](#installation)
    - [Yii compatibility](#yii-compatibility)
    - [Configure](#configure)
        - [Params](#params)
    - [Migrations](#migrations)
    - [Dependencies](#dependencies)
    - [Testing (Unit and Functional)](#testing-unit-and-functional)
        - [Running Tests](#running-tests)
        - [Testing Branches](#testing-branches)
        - [Testing Hints and Tips](#testing-hints-and-tips)

<hr>

## Installation

Add a repository entry to the `compose.json` file:
```
{
    "type": "vcs",
    "url": "git@gitlab.skyts.io:csg/yii/yii2-user.git"
}
```

Add an entry into the `required` property of the `compose.json` file:
```
    "skyline/yii.user": "[version]"
```
Where [version] is the version of this extension.


## Yii compatibility
| Extension Version | Yii Version |
| ----------------- | ----------- |
| 1.0.0 | yii2 >= 2.0.41 |


## Configure

In your configuration file (normally `config/web.php`) add the following entries:
```
    components' => [
        // ... other components ...
        'user' => [
            'identityClass' => 'skyline\yii\user\models\User',
            'enableAutoLogin' => true,
        ],
    ],
    'modules' => [
        // ... other modules ...
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

            // If you want to override the default layout
            // If you want to use the default layout within the module, set this
            // to '@vendor/skylineos/yii.user/views/layouts/login'
            'layout' => '@app/.../views/layouts/<layout>'

            /**
             * If you want to override the views folder. It will look for a folder 'users' in whatever you specify,
             * so, don't add 'user' to your path. Once you've run the migration, you can use gii/crud to generate the
             * views for you in your specified namespace (super helpful if you have a template registered with gii)
             */
            'viewPath' => '@app/.../views/'
        ],
    ]
```

Also add a `route` rule to point to the User views / controllers:
```
    "components" => [...],
    "modules" => [
        // ... other modules ...
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/cms/login' => '/user/user/login',
                '/cms/' => '/cms/index',
                '/cms/users/<action>' => '/user/user/<action>',
                '/cms/users/' => '/user/user',
                '/' => '/',
                '<slug:.*>' => '/site/render'
            ],
        ],
        // ... other modules ...
    ]
```

In your `config/console.php` add the routes for this module's commands (if you would like to use them)
```
    'bootstrap' => [
        // ... other bootstrap components ...
        'user'
    ],
    'modules' => [
        // ... other modules ...
        'user' => [
            'class' => 'skyline\yii\user\Module',
        ],
    ],
```

### Params

Add the following parameters to the `params` key of your configuration file:
```
'supportEmail' => '<email address>',
'supportEmailDisplayName' => 'Skyline Dude',
'passwordRecoverySubject' => 'Forgot your password?',
'newUserEmailSubject' => 'Please finish setting up your account',
```

| Key                     | Type   | Description                               | Required |
|-------------------------|--------|-------------------------------------------|----------|
| supportEmail            | string | 'from' Email address                      | Yes      |
| supportEmailDisplayName | string | 'from' Display name                       | Yes      |
| passwordRecoverySubject | string | Email subject for password recovery email | No       |
| newUserEmailSubject     | string | Email subject for new user email          | No       |

## Migrations

In order to ensure that your database is setup properly, you will need to run the migrations located in the extension.

`php yii migrate/up --migrationPath=@vendor/skylineos/yii.user/migrations`

If something goes wrong with the migration, remove any lingering changes with this command:

`php yii migrate/down --migrationPath=@vendor/skylineos/yii.user/migrations`


## Dependencies
- `skyline\yii\metronic: >=1.0.0` (accessed with the `git@gitlab.skyts.io:csg/yii/yii2.module.metronic.git` repository entry)
- `aws/aws-sdk-php: *`, for mail (deprecated for posix)

## Testing (Unit and Functional)

This package ships with configuration and docker-compose for easy testing. Docker-compose will give you access to php
extensions, xdebug (for code coverage), composer (will install your dependencies), and a database for functional testing.

1. `docker-compose build` *only necessary if this is your first run or if you've modified the Dockerfile*
1. `docker-compose up -d`
1. `docker-compose exec php bash`

### Running Tests

Assuming you're bashed into the php container and in /app

1. `./vendor/bin/codecept run`
1. `./vendor/bin/codecept --help`

### Testing Branches

If you feel the need or think it's generally a good idea (it is), feel free to branch from `develop`. Otherwise,
for smaller updates, be sure to do all of your work on the `develop` branch. You are expected to submit a merge request.

### Testing Hints and Tips

The team largely uses VSCode, it is *recommended* that you do as well. If you find extensions or workflows that are
mighty helpful, please feel free to add them here.