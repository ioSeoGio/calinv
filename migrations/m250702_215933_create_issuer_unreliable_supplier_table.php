<?php

use yii\db\Migration;

class m250702_215933_create_issuer_unreliable_supplier_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%issuer_unreliable_supplier}}', [
            'id' => $this->primaryKey(),

            '_pid' => $this->string()->notNull(),
            'uuid' => $this->string()->notNull(),
            'chainUuid' => $this->string()->notNull(),
            'authorUuid' => $this->string()->notNull(),
            'authorInitials' => $this->string()->notNull(),
            'state' => $this->string()->notNull(),
            'issuerName' => $this->string()->notNull(),
            'issuerAddress' => $this->string()->notNull(),
            'registrationNumber' => $this->string()->notNull(),
            '_addDate' => $this->string()->notNull(),
            '_deleteDate' => $this->string()->null(),
            'reason' => $this->text()->notNull(),

            '_lastApiUpdateDate' =>  $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%issuer_unreliable_supplier}}');
    }
}
