<?php

use yii\db\Migration;

class m250802_085003_create_issuer_additional_info_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%issuer_additional_info}}', [
            'id' => $this->primaryKey(),

            'issuerId' => $this->integer()->notNull(),

            'orderlyCourtAmountAsClaimant' => $this->integer()->null(),
            'orderlyCourtAmountAsDebtor' => $this->integer()->null(),
            'courtAmountAsPlaintiff' => $this->integer()->null(),
            'courtAmountAsDefendant' => $this->integer()->null(),
            'courtAmountOther' => $this->integer()->null(),
            'purchaseArchiveAsSupplierAmount' => $this->integer()->null(),
            'purchaseArchiveAsCustomerAmount' => $this->integer()->null(),
            'purchaseArchiveAsMemberAmount' => $this->integer()->null(),
            'registerOfContractsAsSupplierAmount' => $this->integer()->null(),
            'registerOfContractsAsCustomerAmount' => $this->integer()->null(),

            'retailFacilities' => $this->json()->defaultValue('{}'),
            'kgkPlannedInspectionAmount' => $this->integer()->null(),
            'kgkEndedInspectionAmount' => $this->integer()->null(),

            'isBankrupting' => $this->boolean()->null(),
            'debtTaxes' => $this->json()->defaultValue('{}'),
            'debtFszn' => $this->json()->defaultValue('{}'),
            'increasedEconomicOffense' => $this->json()->defaultValue('{}'),

            'directorName' => $this->string()->null(),
            'trademarksRegisteredAmount' => $this->integer()->null(),
            'industrialProductsAmount' => $this->integer()->notNull(),
            'softRegisteredAmount' => $this->integer()->notNull(),
            'foreignBranchesRfAmount' => $this->integer()->notNull(),

            '_lastApiUpdateDate' =>  $this->string()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%issuer_additional_info}}');
    }
}
