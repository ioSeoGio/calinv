<?php

use src\Entity\User\User;
use yii\db\Migration;

class m250622_191227_create_user_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(64)->notNull(),
            'email' => $this->string(64)->notNull(),
            'auth_key' => $this->string(64),
            'password_hash' => $this->string(64)->notNull(),
            'isPortfolioVisible' => $this->boolean()->notNull(),
            'isPortfolioPublic' => $this->boolean()->notNull(),

            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $passwordHash = Yii::$app->getSecurity()->generatePasswordHash('390390');
        $this->insert('{{%user}}', [
            'username' => 'seog',
            'password_hash' => $passwordHash,
            'email' => 'ioseogio@gmail.com',
            'isPortfolioPublic' => true,
            'isPortfolioVisible' => true,
        ]);
        $this->insert('{{%user}}', [
            'username' => 'kenris',
            'password_hash' => $passwordHash,
            'email' => 'vladislavdistant@mail.ru',
            'isPortfolioPublic' => true,
            'isPortfolioVisible' => true,
        ]);

        $auth = Yii::$app->authManager;
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $auth->assign($admin, 1);
        $auth->assign($admin, 2);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%user}}');
    }
}
