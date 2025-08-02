<?php

namespace src\Entity\Issuer\FinanceReport\CashFlowReport;

use DateInterval;
use DateTimeImmutable;
use src\Entity\DataTypeEnum;
use src\Entity\Issuer\Issuer;
use src\Integration\Legat\FinanceReportFetcherInterface;

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
            date: DateTimeImmutable::createFromFormat('!Y', $dto->year)->sub(DateInterval::createFromDateString('1 year')),
            dto: CashFlowReportDto::fromApiLastYear($dto),
            dataType: $dto->isMock ? DataTypeEnum::mockData : DataTypeEnum::fetchedFromApi,
        );
        $accountingBalance->save();

        $accountingBalance = CashFlowReport::createOrUpdate(
            issuer: $issuer,
            date: $date,
            dto: CashFlowReportDto::fromApiCurrentYear($dto),
            dataType: $dto->isMock ? DataTypeEnum::mockData : DataTypeEnum::fetchedFromApi,
        );
        $accountingBalance->save();

        return $accountingBalance;
    }
}