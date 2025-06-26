<?php

use yii\db\Migration;

class m250625_132527_create_personal_share_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%personal_share}}', [
            'id' => $this->primaryKey(),

            'share_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'buyPrice' => $this->float()->notNull(),
            'amount' => $this->integer()->notNull(),
            'boughtAt' => $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%personal_share}}');
    }
}
