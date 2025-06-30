<?php

use yii\db\Migration;

class m250628_173807_create_business_reputation_info_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%business_reputation_info}}', [
            'id' => $this->primaryKey(),

            'issuerName' => $this->string()->notNull(),
            '_pid' => $this->string()->notNull(),
            '_rating' => $this->string()->notNull(),
            '_lastUpdateDate' => $this->string()->notNull(),
            'pressReleaseLink' => $this->text()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%business_reputation_info}}');
    }
}
