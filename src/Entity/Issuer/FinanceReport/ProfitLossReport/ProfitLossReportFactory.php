<?php

namespace src\Entity\Issuer\FinanceReport\ProfitLossReport;

use DateTimeImmutable;
use src\Entity\DataTypeEnum;
use src\Entity\Issuer\Issuer;
use src\Integration\FinanceReport\FinanceReportFetcherInterface;

class ProfitLossReportFactory
{
    public function __construct(
        private FinanceReportFetcherInterface $financeReportFetcher,
    ) {
    }

    public function createOrUpdateByExternalApi(
        Issuer $issuer,
        DateTimeImmutable $date,
    ): ProfitLossReport {
        $dto = $this->financeReportFetcher->getProfitLoss($issuer->pid, $date);

        $accountingBalance = ProfitLossReport::createOrUpdate(
            issuer: $issuer,
            date: $date,
            dto: $dto,
            dataType: $dto->isMock ? DataTypeEnum::mockData : DataTypeEnum::fetchedFromApi,
        );

        $accountingBalance->save();
        return $accountingBalance;
    }
}