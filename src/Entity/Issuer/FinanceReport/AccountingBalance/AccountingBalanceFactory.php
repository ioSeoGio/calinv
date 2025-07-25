<?php

namespace src\Entity\Issuer\FinanceReport\AccountingBalance;

use DateTimeImmutable;
use src\Action\Issuer\FinancialReport\AccountingBalance\AccountingBalanceCreateForm;
use src\Entity\DataTypeEnum;
use src\Entity\Issuer\Issuer;
use src\Integration\FinanceReport\Dto\FinanceReportAccountingBalanceDto;
use src\Integration\FinanceReport\FinanceReportFetcherInterface;

class AccountingBalanceFactory
{
    public function __construct(
        private FinanceReportFetcherInterface $financeReportFetcher,
    ) {
    }

    public function createOrUpdate(
        Issuer $issuer,
        AccountingBalanceCreateForm $form,
    ): AccountingBalance {
        $dto = new FinanceReportAccountingBalanceDto();
        $dto->_190 = $form->longAsset;
        $dto->_290 = $form->shortAsset;
        $dto->_490 = $form->capital;
        $dto->_590 = $form->longDebt;
        $dto->_690 = $form->shortDebt;

        $accountingBalance = AccountingBalance::createOrUpdate(
            issuer: $issuer,
            date: new \DateTimeImmutable($form->year),
            dto: $dto,
            dataType: DataTypeEnum::createdManually,
        );

        $accountingBalance->save();
        return $accountingBalance;
    }

    public function createOrUpdateByExternalApi(
        Issuer $issuer,
        DateTimeImmutable $date,
    ): AccountingBalance {
        $dto = $this->financeReportFetcher->getAccountingBalance($issuer->pid, $date);

        $accountingBalance = AccountingBalance::createOrUpdate(
            issuer: $issuer,
            date: $date,
            dto: $dto,
            dataType: $dto->isMock ? DataTypeEnum::mockData : DataTypeEnum::fetchedFromApi,
        );

        $accountingBalance->save();
        return $accountingBalance;
    }
}