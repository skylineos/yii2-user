<?php

use skyline\yii\user\models\Email;
use yii\swiftmailer\Mailer;
use yii\swiftmailer\Message;
use Codeception\Util\Stub;

class EmailTest extends \Codeception\Test\Unit
{
    use Codeception\AssertThrows;

    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {

    }

    // tests
    public function testEmailFailsValidationWithEmptyAttributes()
    {
        $email = new Email();
        $this->assertFalse($email->validate());
        [
            'subject' => $subjectErrors,
            'toEmail' => $toEmailErrors,
            'template' => $templateErrors,
        ] = $email->getErrors();
        $this->assertEquals(count($subjectErrors), 1);
        $this->assertEquals($subjectErrors[0], 'Subject cannot be blank.');
        $this->assertEquals(count($toEmailErrors), 1);
        $this->assertEquals($toEmailErrors[0], 'To Email cannot be blank.');
        $this->assertEquals(count($templateErrors), 1);
        $this->assertEquals($templateErrors[0], 'Template cannot be blank.');
    }

    public function testEmailFailsValidationWithInvalidToEmailAtrribute()
    {
        $email = new Email();
        $email->toEmail = 'foo';
        $this->assertFalse($email->validate('toEmail'));
        [
            'toEmail' => $toEmailErrors,
        ] = $email->getErrors();

        $this->assertEquals(count($toEmailErrors), 1);
        $this->assertEquals($toEmailErrors[0], 'To Email is not a valid email address.');
        $email->toEmail = 'foo@bar.com';
        $this->assertTrue($email->validate('toEmail'));
    }

    public function testEmailFailsValidationWithInvalidSubject()
    {
        $subject = str_repeat('-', 256);
        $email = new Email();
        $email->subject = $subject;
        $this->assertFalse($email->validate('subject'));
        [
            'subject' => $subjectErrors,
        ] = $email->getErrors();
        $this->assertEquals(count($subjectErrors), 1);
        $this->assertStringStartsWith('Subject should contain at most', $subjectErrors[0]);
        $email->subject = 'This is a valid subject';
        $this->assertTrue($email->validate('subject'));
    }

    public function testEmailFailsValidationWithInvalidTemplate()
    {
        $template = str_repeat('a', 256);
        $email = new Email();
        $email->template = $template;
        $this->assertFalse($email->validate('template'));
        [
            'template' => $templateErrors,
        ] = $email->getErrors();
        $this->assertEquals(count($templateErrors), 1);
        $this->assertStringStartsWith('Template should contain at most', $templateErrors[0]);
        $email->template = "A valid template";
        $this->assertTrue($email->validate('template'));
    }

    public function testEmailFullValidationPasses()
    {
        $email = new Email();
        $email->subject = 'Foo bar';
        $email->template = 'The quick brown fox';
        $email->toEmail = 'bar@foo.com';
        $this->assertTrue($email->validate());
    }

    public function testEmailFailsValidationWithInvalidFromEmail()
    {
        $email = new Email();
        $email->fromEmail = 'bar';

        $this->assertFalse($email->validate('fromEmail'));
        [
            'fromEmail' => $fromEmailErrors,
        ] = $email->getErrors();

        $this->assertEquals(count($fromEmailErrors), 1);
        $this->assertEquals($fromEmailErrors[0], 'From Email is not a valid email address.');
        $email->fromEmail = 'foo@bar.com';
        $this->assertTrue($email->validate('fromEmail'));
    }

    public function testEmailFailsValidationWithInvalidFromName()
    {
        $fromName = str_repeat('-', 256);
        $email = new Email();
        $email->fromName = $fromName;
        $this->assertFalse($email->validate('fromName'));
        [
            'fromName' => $fromNameErrors,
        ] = $email->getErrors();
        $this->assertEquals(count($fromNameErrors), 1);
        $this->assertStringStartsWith('From Name should contain at most', $fromNameErrors[0]);
        $email->fromName = 'This is a valid fromName';
        $this->assertTrue($email->validate('fromName'));
    }

    public function testCreateEmailWithFromemailAndFromname()
    {
        $fromEmail = 'adummy@skylinenet.net';
        $fromName = 'Alvin Dummy';

        $email = new Email([
            'fromEmail' => $fromEmail,
            'fromName' => $fromName
        ]);

        $this->assertEquals($email->fromName, $fromName);
        $this->assertEquals($email->fromEmail, $fromEmail);
    }

    public function testSendEmail()
    {
        /**
         * @see https://github.com/yiisoft/yii2/issues/14718 for issues with the default
         * TestMailer object. -_-
         */
        $mailer = Stub::makeEmpty(
            new Mailer(),
            [
                'compose' => Stub::make(
                    new Message(),
                    [
                        'send' => Stub::consecutive(true, false)
                    ]
                )
            ]
        );
        \Yii::$app->set('mailer', $mailer);
        $email = new Email();
        $email->toEmail = 'foo@bar.com';
        $email->subject = 'Test email';
        $email->template = '@mail/create-account-html';
        $this->assertTrue($email->sendEmail());
        $this->assertFalse($email->sendEmail());
    }
}
