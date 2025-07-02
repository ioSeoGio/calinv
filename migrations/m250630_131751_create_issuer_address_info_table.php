<?php

use yii\db\Migration;

class m250630_131751_create_issuer_address_info_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%issuer_address_info}}', [
            'id' => $this->primaryKey(),

            '_pid' => $this->string()->notNull(),

            'country' => $this->string()->notNull(),
            'settlementType' => $this->string()->notNull(),
            'settlementName' => $this->string()->notNull(),
            'placeType' => $this->string()->notNull(),
            'placeName' => $this->string()->notNull(),
            'houseNumber' => $this->string()->notNull(),
            'roomType' => $this->string()->null(),
            'roomNumber' => $this->string()->null(),

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
