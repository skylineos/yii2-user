This module is new and thrown together from a handful of places. It seems functional, but probably needs
a lot more eyes-on if it's going to have wide-spread adoption.

### Installation

1. Clone this module to @app/module/User (@todo git submodule?)
2. Create the User table
    * **In docker (development):** `docker-compose exec yii2 php yii migrate/up --migrationPath=@app/modules/user/migrations`
    * **Production:** php yii migrate/up --migrationPath=@app/modules/user/migrations`
3. Configure params in `config/params.php` (see params section below)
4. Tell yii to use this module for user management
    1. edit `config/web.php` ,find and change (or add) the `user` block in `components` as follows
    ```
    'components' => [
        // ... other components ...
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'enableAutoLogin' => true,
        ],
    ]
    ```
5. Add this module to the bootstrap for easy routing/reference
    1. edit `config/web.php`, find the `modules` block (or add it if it doesn't exist) and add the following:
    ```
    'components' => [
        ...
    ],
    'modules' => [
        .// ... other modules ...
        'user' => [
            // Required Parameters
            'class' => 'app\modules\user\Module',
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
            'rememberMeExpiration' => 3600*24*30,

            // If you want to override the default layout
            // If you want to use the default layout within the module, set this
            // to '@app/modules/user/views/layouts/login'
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
6. Edit `config/console.php` and add the routes for this module's commands (if you would like to use them)
    ```
    'bootstrap' => [
        // ... other bootstrap components ...
        'user'
    ],
    'modules' => [
        // ... other modules ...
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
    ],
    ```

### Params

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

### Migrations

`php yii migrate/up --migrationPath=@app/modules/user/migrations`
`php yii migrate/down --migrationPath=@app/modules/user/migrations`


### Dependencies

The following are required for this module to work

* `composer require aws/aws-sdk-php`
    * SES configured @ AWS
* `https://gitlab.skyts.io/csg/yii2.module.metronic`