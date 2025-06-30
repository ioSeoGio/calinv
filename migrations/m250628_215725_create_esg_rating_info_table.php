<?php

use yii\db\Migration;

class m250628_215725_create_esg_rating_info_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%esg_rating_info}}', [
            'id' => $this->primaryKey(),

            'issuerName' => $this->string()->notNull(),
            '_pid' => $this->string()->null(), // @todo Мб временно, пересмотреть заполнение УНП по имени эмитента,
            // мб есть вариант тянуть имя с другой апи и оно будет совпадать
            '_forecast' => $this->string()->notNull(),
            '_rating' => $this->string()->notNull(),
            '_category' => $this->string()->notNull(),

            '_assignmentDate' => $this->string()->notNull(),
            '_lastUpdateDate' => $this->string()->notNull(),

            'pressReleaseLink' => $this->text()->notNull(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%esg_rating_info}}');
    }
}
