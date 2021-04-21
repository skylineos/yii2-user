<?php

use Codeception\Util\Stub;
use yii\db\Connection;
use app\modules\user\models\User;
use app\tests\fixtures\UserFixture;
use skyline\user\tests\mocks\DBMock;

class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    // tests
    public function testSetResetToken()
    {
        \Yii::$app->set('db', DBMock::getConnection([
            'columns' => [
                [
                    'dbType' => 'int',
                    'isPrimaryKey' => true,
                    'name' => 'id',
                ],
                [
                    'dbType' => 'string',
                    'name' => 'passwordResetToken',
                ],
                [
                    'dbType' => 'string',
                    'name' => 'passwordResetTokenExp',
                ],
            ]
        ]));
        $tableSchema = \Yii::$app->db
            ->getSchema()
            ->getTableSchema(User::tableName());
        $user = new User();
        $this->assertNull($user->passwordResetToken);
        $this->assertNull($user->passwordResetTokenExp);
        $user->setResetToken();
        $this->assertTrue(is_string($user->passwordResetToken));
        $this->assertTrue(is_string($user->passwordResetTokenExp));
    }
}
