<?php

use yii\db\Migration;

class m250701_090404_create_table_issuer_type_of_activity extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%issuer_type_of_activity}}', [
            'id' => $this->primaryKey(),

            '_pid' => $this->string()->notNull(),
            '_activityFromDate' => $this->string()->notNull(),
            '_activityToDate' => $this->string()->null(),
            'isActive' => $this->boolean()->notNull(),
            'code' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),

            '_lastApiUpdateDate' =>  $this->string()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%issuer_address_info}}');
    }
}
