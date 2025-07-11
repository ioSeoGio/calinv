<?php

use yii\db\Migration;

class m250710_111535_create_issuer_profit_loss_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%issuer_profit_loss_report}}', [
            'id' => $this->primaryKey(),

            'issuer_id' => $this->integer()->notNull(),
            '_termType' => $this->string()->notNull(),
            '_year' => $this->string()->notNull(),
            '_dataType' => $this->string()->notNull(),

            '_010' => $this->float()->null(),
            '_020' => $this->float()->null(),
            '_030' => $this->float()->null(),
            '_040' => $this->float()->null(),
            '_050' => $this->float()->null(),
            '_060' => $this->float()->null(),
            '_070' => $this->float()->null(),
            '_080' => $this->float()->null(),
            '_090' => $this->float()->notNull(),

            '_100' => $this->float()->null(),
            '_101' => $this->float()->null(),
            '_102' => $this->float()->null(),
            '_103' => $this->float()->null(),
            '_104' => $this->float()->null(),
            '_110' => $this->float()->null(),
            '_111' => $this->float()->null(),
            '_112' => $this->float()->null(),

            '_120' => $this->float()->null(),
            '_121' => $this->float()->null(),
            '_122' => $this->float()->null(),

            '_130' => $this->float()->null(),
            '_131' => $this->float()->null(),
            '_132' => $this->float()->null(),
            '_133' => $this->float()->null(),

            '_140' => $this->float()->null(),
            '_150' => $this->float()->null(),
            '_160' => $this->float()->null(),
            '_170' => $this->float()->null(),
            '_180' => $this->float()->null(),
            '_190' => $this->float()->null(),
            '_210' => $this->float()->notNull(),
            '_220' => $this->float()->null(),
            '_230' => $this->float()->null(),
            '_240' => $this->float()->notNull(),

            '_lastApiUpdateDate' =>  $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%issuer_profit_loss_report}}');
    }
}
