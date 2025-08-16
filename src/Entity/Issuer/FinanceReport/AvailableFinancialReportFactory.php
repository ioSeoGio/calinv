<?php

namespace src\Entity\Issuer\FinanceReport;

use src\Entity\Issuer\Issuer;
use src\Integration\Legat\Dto\AvailableFinancialReports\AllAvailableFinancialReportsDto;
use src\Integration\Legat\Dto\AvailableFinancialReports\AvailableFinancialReportsDto;

class AvailableFinancialReportFactory
{
    public function createOrUpdateBulk(
        Issuer $issuer,
        AllAvailableFinancialReportsDto $allDto,
    ): void {
        foreach ($allDto->records as $dto) {
            $this->createOrUpdate($issuer, $dto);
        }
    }

    public function createOrUpdate(
        Issuer $issuer,
        AvailableFinancialReportsDto $availableFinancialReportsDto,
    ): AvailableFinancialReportData {
        $model = AvailableFinancialReportData::createOrUpdate(
            issuer: $issuer,
            year: \DateTimeImmutable::createFromFormat('Y', $availableFinancialReportsDto->year),
            hasCapitalChangeReport: $availableFinancialReportsDto->hasCapitalChangeReport(),
            hasCashFlowReport: $availableFinancialReportsDto->hasCashFlowReport(),
            hasProfitLossReport: $availableFinancialReportsDto->hasProfitLossReport(),
            hasAccountingBalance: $availableFinancialReportsDto->hasAccountingBalance(),
        );
        $model->save();

        return $model;
    }
}