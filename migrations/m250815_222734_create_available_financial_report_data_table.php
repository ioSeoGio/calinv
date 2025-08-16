<?php

use yii\db\Migration;

class m250815_222734_create_available_financial_report_data_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%available_financial_report_data}}', [
            'id' => $this->primaryKey(),

            'issuerId' => $this->integer()->notNull(),
            '_year' => $this->integer()->notNull(),

            'hasAccountingBalance' => $this->boolean()->notNull(),
            'hasProfitLossReport' => $this->boolean()->notNull(),
            'hasCapitalChangeReport' => $this->boolean()->notNull(),
            'hasCashFlowReport' => $this->boolean()->notNull(),

            '_lastApiUpdateDate' => $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%available_financial_report_data}}');
    }
}
