<?php

use yii\db\Migration;

class m250702_133049_create_issuer_event_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%issuer_event}}', [
            'id' => $this->primaryKey(),

            '_pid' => $this->string()->notNull(),
            '_eventDate' => $this->string()->notNull(),
            'eventName' => $this->string()->notNull(),

            '_lastApiUpdateDate' => $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%issuer_event}}');
    }
}
