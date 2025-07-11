<?php

namespace src\Entity\Issuer\FinanceReport\CashFlowReport;

use DateTimeImmutable;
use src\Entity\DataTypeEnum;
use src\Entity\Issuer\Issuer;
use src\Integration\FinanceReport\FinanceReportFetcherInterface;

class CashFlowReportFactory
{
    public function __construct(
        private FinanceReportFetcherInterface $financeReportFetcher,
    ) {
    }

    public function createOrUpdateByExternalApi(
        Issuer $issuer,
        DateTimeImmutable $date,
    ): CashFlowReport {
        $dto = $this->financeReportFetcher->getCashFlowReport($issuer->pid, $date);

        $accountingBalance = CashFlowReport::createOrUpdate(
            issuer: $issuer,
            date: $date,
            dto: $dto,
            dataType: $dto->isMock ? DataTypeEnum::mockData : DataTypeEnum::fetchedFromApi,
        );

        $accountingBalance->save();
        return $accountingBalance;
    }
}