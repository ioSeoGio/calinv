<?php

use yii\db\Migration;

class m250802_100647_create_issuer_liquidation_info_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%issuer_liquidation_info}}', [
            'id' => $this->primaryKey(),

            'issuerId' => $this->integer()->notNull(),
            '_beginDate' => $this->string()->notNull(),
            '_publicationDate' => $this->string()->notNull(),
            '_currentStatusClaimDate' => $this->string()->null(),

            'liquidationDecisionNumber' => $this->string()->notNull(),
            'liquidatorName' => $this->string()->notNull(),
            'liquidatorAddress' => $this->string()->notNull(),
            'liquidatorPhone' => $this->string()->notNull(),
            'periodForAcceptingClaimsInMonths' => $this->integer()->notNull(),
            'status' => $this->string()->notNull(),

            '_lastApiUpdateDate' =>  $this->string()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%issuer_liquidation_info}}');
    }
}
