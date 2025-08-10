<?php

use yii\db\Migration;

class m250809_172737_create_share_deal_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%share_deal}}', [
            'id' => $this->primaryKey(),

            'share_id' => $this->integer()->notNull(),
            '_date' => $this->string()->notNull(),
            'timestamp' => $this->bigInteger()->null(),
            'currency' => $this->string()->notNull(),

            'minPrice' => $this->float()->notNull(),
            'maxPrice' => $this->float()->notNull(),
            'weightedAveragePrice' => $this->float()->notNull(),

            'totalSum' => $this->float()->notNull(),
            'totalAmount' => $this->integer()->notNull(),
            'totalDealAmount' => $this->integer()->notNull(),
            '_lastApiUpdateDate' => $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%share_deal}}');
    }
}
