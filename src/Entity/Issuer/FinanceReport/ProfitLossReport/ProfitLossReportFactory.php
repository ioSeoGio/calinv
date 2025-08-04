<?php

namespace src\Entity\Issuer\FinanceReport\ProfitLossReport;

use DateInterval;
use DateTimeImmutable;
use src\Action\Issuer\FinancialReport\ProfitLossReport\ProfitLossReportCreateForm;
use src\Entity\DataTypeEnum;
use src\Entity\Issuer\Issuer;
use src\Integration\Legat\FinanceReportFetcherInterface;

class ProfitLossReportFactory
{
    public function __construct(
        private FinanceReportFetcherInterface $financeReportFetcher,
    ) {
    }

    public function createOrUpdate(
        Issuer $issuer,
        ProfitLossReportCreateForm $form,
    ): ProfitLossReport {
        $dto = new ProfitLossReportDto(
            _010: $form->_010,
            _090: $form->_090,
            _210: $form->_210,
            _240: $form->_240,
        );

        $accountingBalance = ProfitLossReport::createOrUpdate(
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
    ): ProfitLossReport {
        $dto = $this->financeReportFetcher->getProfitLoss($issuer->pid, $date);

        $accountingBalance = ProfitLossReport::createOrUpdate(
            issuer: $issuer,
            date: DateTimeImmutable::createFromFormat('!Y', $dto->year)->sub(DateInterval::createFromDateString('1 year')),
            dto: ProfitLossReportDto::fromApiLastYear($dto),
            dataType: $dto->isMock ? DataTypeEnum::mockData : DataTypeEnum::fetchedFromApi,
        );
        $accountingBalance->save();

        $accountingBalance = ProfitLossReport::createOrUpdate(
            issuer: $issuer,
            date: $date,
            dto: ProfitLossReportDto::fromApiCurrentYear($dto),
            dataType: $dto->isMock ? DataTypeEnum::mockData : DataTypeEnum::fetchedFromApi,
        );
        $accountingBalance->save();

        return $accountingBalance;
    }
}