<?php

namespace app\modules\user;

use yii\base\BootstrapInterface;

/**
 * module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\user\controllers';

    /**
     * @var string $supportEmail - set in config/params. Required
     */
    public ?string $supportEmail = null;

    /**
     * @var string $supportEmailDisplayName - set in config/params. Required
     */
    public ?string $supportEmailDisplayName = null;

    /**
     * @var string $newUserEmailSubject - override in config/params. Optional
     */
    public string $newUserEmailSubject = 'Please finish setting up your account';

    /**
     * @var string $passwordRecoverySubject - override in config/params. Optional
     */
    public string $passwordRecoverySubject = 'Recover your lost password';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!\Yii::$app->params['supportEmail']) {
            throw new \yii\web\BadRequestHttpException('Please specify supportEmail in params');
        } else {
            $this->supportEmail = \Yii::$app->params['supportEmail'];
        }

        if (!\Yii::$app->params['supportEmailDisplayName']) {
            throw new \yii\web\BadRequestHttpException('Please specify supportEmailDisplayName in params');
        } else {
            $this->supportEmailDisplayName = \Yii::$app->params['supportEmailDisplayName'];
        }

        if (\Yii::$app->params['newUserEmailSubject']) {
            $this->newUserEmailSubject = \Yii::$app->params['newUserEmailSubject'];
        }

        if (\Yii::$app->params['passwordRecoverySubject']) {
            $this->passwordRecoverySubject = \Yii::$app->params['passwordRecoverySubject'];
        }

        parent::init();

        // custom initialization code goes here
        $this->modules = [];
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\user\commands';
        }
    }
}
