<?php

namespace app\modules\user\models;

use yii\base\Model;

class Email extends Model
{
    /**
     * @var ?string $subject the subject of the email to send
     */
    public $subject = null;

    /**
     * @var $fromEmail the email address of the sender
     */
    public $fromEmail = null;

    /**
     * @var $fromName the name of the sender
     */
    public $fromName = null;

    /**
     * @var $toEmail the email address of the recipient
     */
    public $toEmail = null;

    /**
     * @var $template the email template/view file
     */
    public $template = null;

    /**
     * @var array $params any additional params to pass to the template
     */
    public array $params = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['params'], 'safe'],
            [['subject', 'toEmail', 'template'], 'required'],
            [['subject', 'fromName', 'template'], 'string', 'max' => 255],
            [['fromEmail', 'toEmail'], 'email'],
            ['fromEmail', 'default', 'value' => \Yii::$app->params['supportEmail']],
            ['fromName', 'default', 'value' => \Yii::$app->params['supportEmailDisplayName']],
        ];
    }

    public function init()
    {
        if ($this->fromEmail === null) {
            $this->fromEmail = \Yii::$app->params['supportEmail'];
        }
        if ($this->fromName === null) {
            $this->fromName = \Yii::$app->params['supportEmailDisplayName'];
        }
    }

    public function sendEmail() : bool
    {
        $message = \Yii::$app
            ->mailer
            ->compose($this->template, $this->params)
            ->setFrom([
                $this->fromEmail => $this->fromName,
                ])
            ->setTo($this->toEmail)
            ->setSubject($this->subject);

        if ($message->send()) {
            return true;
        }

        return false;
    }
}
