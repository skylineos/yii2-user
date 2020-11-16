<?php

use yii\db\Migration;
use app\modules\user\models\User;

/**
 * Class m201111_213534_create_table_User
 */
class m201111_213534_create_table_User extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('User', [
            'id' => $this->primaryKey(),
            'authKey' => $this->string(32),
            'passwordResetToken' => $this->string(),
            'lastLogin' => $this->datetime()->defaultExpression('CURRENT_TIMESTAMP'),
            'email' => $this->string()->notNull()->unique(),
            'passwordHash' => $this->string()->notNull(),
            'name' => $this->string(150)->notNull(),
            'status' => $this->smallInteger()->defaultValue(User::STATUS_ACTIVE),
            'dateCreated' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'lastModified' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'modifiedBy' => $this->integer()->notNull(),
            'passwordResetTokenExp' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-User-modifiedBy-User-id',
            'User',
            'modifiedBy',
            'User',
            'id',
            'CASCADE'
        );

        echo "\n\nUse the command user/user/add to create a new user.\n\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-User-modifiedBy-User-id',
            'User'
        );

        $this->dropTable('User');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201111_213534_create_table_User cannot be reverted.\n";

        return false;
    }
    */
}
