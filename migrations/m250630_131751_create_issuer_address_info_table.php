<?php

use yii\db\Migration;

class m250630_131751_create_issuer_address_info_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%issuer_address_info}}', [
            'id' => $this->primaryKey(),

            '_pid' => $this->string()->notNull(),

            'fullAddress' => $this->string()->notNull(),
            'email' => $this->string()->null(),
            'site' => $this->string()->null(),
            'phones' => $this->string()->null(),

            '_lastApiUpdateDate' =>  $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%issuer_address_info}}');
    }
}
