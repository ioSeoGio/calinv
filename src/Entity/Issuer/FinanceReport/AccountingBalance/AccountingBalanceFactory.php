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
            _110: $form->_110,
            _120: $form->_120,
            _130: $form->_130,
            _131: $form->_131,
            _140: $form->_140,
            _150: $form->_150,
            _160: $form->_160,
            _170: $form->_170,
            _180: $form->_180,
            _190: $form->_190,

            _210: $form->_210,
            _211: $form->_211,
            _213: $form->_213,
            _214: $form->_214,
            _215: $form->_215,
            _230: $form->_230,
            _240: $form->_240,
            _250: $form->_250,
            _260: $form->_260,
            _270: $form->_270,
            _280: $form->_280,
            _290: $form->_290,
            _300: $form->_190 + $form->_290,

            _410: $form->_410,
            _440: $form->_440,
            _450: $form->_450,
            _460: $form->_460,
            _490: $form->_490,

            _510: $form->_510,
            _540: $form->_540,
            _590: $form->_590,

            _610: $form->_610,
            _620: $form->_620,
            _630: $form->_630,
            _631: $form->_631,
            _632: $form->_632,
            _633: $form->_633,
            _634: $form->_634,
            _635: $form->_635,
            _636: $form->_636,
            _637: $form->_637,
            _638: $form->_638,
            _650: $form->_650,
            _670: $form->_670,
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