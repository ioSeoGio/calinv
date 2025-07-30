<?php

use yii\db\Migration;

class m250711_092856_create_issuer_cash_flow_report_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%issuer_cash_flow_report}}', [
            'id' => $this->primaryKey(),

            'issuer_id' => $this->integer()->notNull(),
            '_termType' => $this->string()->notNull(),
            '_year' => $this->string()->notNull(),
            '_dataType' => $this->string()->notNull(),

            '_020' => $this->float()->notNull(),
            '_021' => $this->float()->null(),
            '_022' => $this->float()->null(),
            '_023' => $this->float()->null(),
            '_024' => $this->float()->null(),

            '_030' => $this->float()->notNull(),
            '_031' => $this->float()->null(),
            '_032' => $this->float()->null(),
            '_033' => $this->float()->null(),
            '_040' => $this->float()->notNull(),

            '_050' => $this->float()->null(),
            '_051' => $this->float()->null(),
            '_052' => $this->float()->null(),
            '_053' => $this->float()->null(),
            '_054' => $this->float()->null(),
            '_055' => $this->float()->null(),

            '_060' => $this->float()->notNull(),
            '_061' => $this->float()->null(),
            '_062' => $this->float()->null(),
            '_063' => $this->float()->null(),
            '_064' => $this->float()->null(),
            '_070' => $this->float()->notNull(),

            '_080' => $this->float()->notNull(),
            '_081' => $this->float()->null(),
            '_082' => $this->float()->null(),
            '_083' => $this->float()->null(),
            '_084' => $this->float()->null(),

            '_090' => $this->float()->notNull(),
            '_091' => $this->float()->null(),
            '_092' => $this->float()->null(),
            '_093' => $this->float()->null(),
            '_094' => $this->float()->null(),
            '_095' => $this->float()->null(),
            '_100' => $this->float()->notNull(),

            '_110' => $this->float()->notNull(),
            '_120' => $this->float()->notNull(),
            '_130' => $this->float()->notNull(),
            '_140' => $this->float()->null(),

            '_lastApiUpdateDate' =>  $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%issuer_cash_flow_report}}');
    }
}
