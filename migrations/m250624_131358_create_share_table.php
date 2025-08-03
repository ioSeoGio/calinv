<?php

use yii\db\Migration;

class m250624_131358_create_share_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%share}}', [
            'id' => $this->primaryKey(),

            'lastDealDate' => $this->string()->null(),
            'lastDealChangePercent' => $this->float()->null(),
            'currentPrice' => $this->float()->null(),

            'fullnessState' => $this->json()->notNull(),
            'issuer_id' => $this->integer()->notNull(),

            'nationalId' => $this->string()->notNull(),
            'orderedIssueId' => $this->integer()->notNull(),
            'registerNumber' => $this->string()->notNull(),
            'denomination' => $this->float()->notNull(),

            'simpleIssuedAmount' => $this->bigInteger()->notNull(),
            'privilegedIssuedAmount' => $this->bigInteger()->notNull(),
            'totalIssuedAmount' => $this->bigInteger()->notNull(),

            'issueDate' => $this->string()->notNull(),
            'closingDate' => $this->string()->null(),

            '_lastApiUpdateDate' =>  $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%share}}');
    }
}
