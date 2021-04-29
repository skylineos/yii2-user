<?php

use Codeception\Util\Stub;
use yii\db\Connection;
use skyline\yii\user\models\User;
use app\tests\fixtures\UserFixture;
use skyline\user\tests\mocks\DBMock;
use skyline\tests\mocks\Mocker;

class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    // tests
    public function testSetResetToken()
    {
        $user = new User();
        $this->assertNull($user->passwordResetToken);
        $this->assertNull($user->passwordResetTokenExp);
        $user->setResetToken();
        $this->assertTrue(is_string($user->passwordResetToken));
        $this->assertTrue(is_string($user->passwordResetTokenExp));
    }
}
