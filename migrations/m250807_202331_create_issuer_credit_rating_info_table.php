<?php

use yii\db\Migration;

class m250807_202331_create_issuer_credit_rating_info_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%issuer_credit_rating_info}}', [
            'id' => $this->primaryKey(),

            'issuerName' => $this->string()->notNull(),
            '_forecast' => $this->string()->null(),
            '_rating' => $this->string()->notNull(),

            '_assignmentDate' => $this->string()->notNull(),
            '_lastUpdateDate' => $this->string()->notNull(),

            'pressReleaseLink' => $this->text()->notNull(),

            '_lastApiUpdateDate' =>  $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%issuer_credit_rating_info}}');
    }
}
