<?php

use yii\db\Migration;

class m250802_124951_create_issuer_employee_amount_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%issuer_employee_amount}}', [
            'id' => $this->primaryKey(),

            'issuerId' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'year' => $this->integer()->notNull(),
            '_date' => $this->string()->notNull(),

            '_lastApiUpdateDate' =>  $this->string()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%issuer_employee_amount}}');
    }
}
