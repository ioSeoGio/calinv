<?php

use yii\db\Migration;

class m250628_215725_create_issuer_esg_rating_info_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%issuer_esg_rating_info}}', [
            'id' => $this->primaryKey(),

            'issuerId' => $this->integer()->null(),

            'issuerName' => $this->string()->notNull(),
            '_forecast' => $this->string()->notNull(),
            '_rating' => $this->string()->notNull(),
            '_category' => $this->string()->notNull(),

            '_assignmentDate' => $this->string()->notNull(),
            '_lastUpdateDate' => $this->string()->notNull(),

            'pressReleaseLink' => $this->text()->notNull(),

            '_lastApiUpdateDate' =>  $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%issuer_esg_rating_info}}');
    }
}
