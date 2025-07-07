<?php

namespace src\Entity\Issuer\AccountingBalance;

use src\Action\Issuer\AccountingBalance\AccountingBalanceCreateForm;
use src\Entity\Issuer\Issuer;

class AccountingBalanceFactory
{
    public function createOrUpdate(
        Issuer $issuer,
        AccountingBalanceCreateForm $form,
    ): AccountingBalance {
        $accountingBalance = AccountingBalance::createOrUpdate(
            issuer: $issuer,
            date: new \DateTimeImmutable($form->year),
            longAsset: $form->longAsset,
            shortAsset: $form->shortAsset,
            longDebt: $form->longDebt,
            shortDebt: $form->shortDebt,
            capital: $form->capital,
        );

        $accountingBalance->save();
        return $accountingBalance;
    }
}