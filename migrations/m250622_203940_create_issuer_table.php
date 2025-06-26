<?php

use yii\db\Migration;

class m250622_203940_create_issuer_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%issuer}}', [
            'id' => $this->primaryKey(),

            'name' => $this->string()->notNull(),
            'bikScore' => $this->string()->notNull(),
            'expressRating' => $this->float()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%issuer}}');
    }
}
