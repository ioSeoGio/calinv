<?php

use yii\db\Migration;

class m250624_131358_create_share_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%share}}', [
            'id' => $this->primaryKey(),

            'name' => $this->string()->notNull(),
            'issuer_id' => $this->integer()->notNull(),
            'denomination' => $this->float()->notNull(),
            'currentPrice' => $this->float()->notNull(),
            'volumeIssued' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%share}}');
    }
}
