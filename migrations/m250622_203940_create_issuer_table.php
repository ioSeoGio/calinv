<?php

use yii\db\Migration;

class m250622_203940_create_issuer_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%issuer}}', [
            'id' => $this->primaryKey(),

            'name' => $this->string()->null(),
            'description' => $this->text()->null(),
            '_legalStatus' => $this->string()->null(),
            '_dateFinanceReportsInfoUpdated' => $this->string()->null(),
            '_dateShareInfoModerated' => $this->string()->null(),

            'fullnessState' => $this->json()->notNull(),
            '_pid' => $this->string()->notNull(),

            '_lastApiUpdateDate' =>  $this->string()->null(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%issuer}}');
    }
}
