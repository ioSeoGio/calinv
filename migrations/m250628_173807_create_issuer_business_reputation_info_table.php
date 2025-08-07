<?php

use yii\db\Migration;

class m250628_173807_create_issuer_business_reputation_info_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%issuer_business_reputation_info}}', [
            'id' => $this->primaryKey(),

            'issuerName' => $this->string()->notNull(),
            '_pid' => $this->string()->notNull(),
            '_rating' => $this->string()->notNull(),
            '_expirationDate' => $this->string()->notNull(),
            'pressReleaseLink' => $this->text()->notNull(),

            '_lastApiUpdateDate' => $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%issuer_business_reputation_info}}');
    }
}
