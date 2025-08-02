<?php

namespace src\Entity\Issuer\FinanceReport\ProfitLossReport;

use DateInterval;
use DateTimeImmutable;
use src\Entity\DataTypeEnum;
use src\Entity\Issuer\Issuer;
use src\Integration\Legat\FinanceReportFetcherInterface;

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