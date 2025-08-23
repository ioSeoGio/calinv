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
            '_year' => $this->string()->notNull(),
            '_dataType' => $this->string()->notNull(),

            '_110' => $this->float()->null(),
            '_120' => $this->float()->null(),
            '_130' => $this->float()->null(),
            '_131' => $this->float()->null(),
            '_140' => $this->float()->null(),
            '_150' => $this->float()->null(),
            '_160' => $this->float()->null(),
            '_170' => $this->float()->null(),
            '_180' => $this->float()->null(),
            '_190' => $this->float()->notNull(),

            '_210' => $this->float()->null(),
            '_211' => $this->float()->null(),
            '_213' => $this->float()->null(),
            '_214' => $this->float()->null(),
            '_215' => $this->float()->null(),
            '_230' => $this->float()->null(),
            '_240' => $this->float()->null(),
            '_250' => $this->float()->null(),
            '_260' => $this->float()->null(),
            '_270' => $this->float()->null(),
            '_280' => $this->float()->null(),
            '_290' => $this->float()->notNull(),
            '_300' => $this->float()->notNull(),

            '_410' => $this->float()->null(),
            '_440' => $this->float()->null(),
            '_450' => $this->float()->null(),
            '_460' => $this->float()->null(),
            '_470' => $this->float()->null(),
            '_480' => $this->float()->null(),
            '_490' => $this->float()->notNull(),

            '_510' => $this->float()->null(),
            '_540' => $this->float()->null(),
            '_590' => $this->float()->notNull(),

            '_610' => $this->float()->null(),
            '_620' => $this->float()->null(),
            '_630' => $this->float()->null(),
            '_631' => $this->float()->null(),
            '_632' => $this->float()->null(),
            '_633' => $this->float()->null(),
            '_634' => $this->float()->null(),
            '_635' => $this->float()->null(),
            '_636' => $this->float()->null(),
            '_637' => $this->float()->null(),
            '_638' => $this->float()->null(),
            '_650' => $this->float()->null(),
            '_670' => $this->float()->null(),
            '_690' => $this->float()->notNull(),
            '_700' => $this->float()->notNull(),

            '_lastApiUpdateDate' =>  $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%issuer_accounting_balance}}');
    }
}
