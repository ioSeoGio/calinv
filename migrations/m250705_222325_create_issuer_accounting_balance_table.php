<?php

use yii\db\Migration;

class m250705_222325_create_issuer_accounting_balance_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%issuer_accounting_balance}}', [
            'id' => $this->primaryKey(),

            'issuer_id' => $this->integer()->notNull(),
            '_termType' => $this->string()->notNull(),
            'year' => $this->string()->notNull(),

            'longAsset' => $this->float()->notNull(),
            'shortAsset' => $this->float()->notNull(),
            'longDebt' => $this->float()->notNull(),
            'shortDebt' => $this->float()->notNull(),
            'capital' => $this->float()->notNull(),

            '_lastApiUpdateDate' =>  $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%issuer_accounting_balance}}');
    }
}
