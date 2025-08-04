<?php

namespace src\Entity\Issuer\FinanceReport\AccountingBalance;

use DateInterval;
use DateTimeImmutable;
use src\Action\Issuer\FinancialReport\AccountingBalance\AccountingBalanceCreateForm;
use src\Entity\DataTypeEnum;
use src\Entity\Issuer\Issuer;
use src\Integration\Legat\FinanceReportFetcherInterface;

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
        $dto = new AccountingBalanceDto(
            _190: $form->_190,
            _290: $form->_290,
            _300: $form->_190 + $form->_290,
            _490: $form->_490,
            _590: $form->_590,
            _690: $form->_690,
            _700: $form->_700,
        );

        $accountingBalance = AccountingBalance::createOrUpdate(
            issuer: $issuer,
            date: DateTimeImmutable::createFromFormat('Y', $form->year),
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
            date: DateTimeImmutable::createFromFormat('!Y', $dto->year)->sub(DateInterval::createFromDateString('1 year')),
            dto: AccountingBalanceDto::fromApiLastYear($dto),
            dataType: $dto->isMock ? DataTypeEnum::mockData : DataTypeEnum::fetchedFromApi,
        );
        $accountingBalance->save();

        $accountingBalance = AccountingBalance::createOrUpdate(
            issuer: $issuer,
            date: DateTimeImmutable::createFromFormat('!Y', $dto->year),
            dto: AccountingBalanceDto::fromApiCurrentYear($dto),
            dataType: $dto->isMock ? DataTypeEnum::mockData : DataTypeEnum::fetchedFromApi,
        );
        $accountingBalance->save();

        return $accountingBalance;
    }
}