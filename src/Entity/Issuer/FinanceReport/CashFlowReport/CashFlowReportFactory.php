<?php

namespace src\Entity\Issuer\FinanceReport\CashFlowReport;

use DateInterval;
use DateTimeImmutable;
use src\Action\Issuer\FinancialReport\CashFlowReport\CashFlowReportCreateForm;
use src\Entity\DataTypeEnum;
use src\Entity\Issuer\Issuer;
use src\Integration\Legat\FinanceReportFetcherInterface;

class CashFlowReportFactory
{
    public function __construct(
        private FinanceReportFetcherInterface $financeReportFetcher,
    ) {
    }

    public function createOrUpdate(
        Issuer $issuer,
        CashFlowReportCreateForm $form,
    ): CashFlowReport {
        $dto = new CashFlowReportDto(
            _020: $form->_020,
            _030: $form->_030,
            _040: $form->_040,
            _080: $form->_080,
            _090: $form->_090,
            _100: $form->_100,
            _110: $form->_110,
        );

        $accountingBalance = CashFlowReport::createOrUpdate(
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
    ): CashFlowReport {
        $dto = $this->financeReportFetcher->getCashFlowReport($issuer->pid, $date);

        $cashFlowReport = CashFlowReport::createOrUpdate(
            issuer: $issuer,
            date: DateTimeImmutable::createFromFormat('!Y', $dto->year)->sub(DateInterval::createFromDateString('1 year')),
            dto: CashFlowReportDto::fromApiLastYear($dto),
            dataType: $dto->isMock ? DataTypeEnum::mockData : DataTypeEnum::fetchedFromApi,
        );
        $cashFlowReport->save();

        $cashFlowReport = CashFlowReport::createOrUpdate(
            issuer: $issuer,
            date: $date,
            dto: CashFlowReportDto::fromApiCurrentYear($dto),
            dataType: $dto->isMock ? DataTypeEnum::mockData : DataTypeEnum::fetchedFromApi,
        );
        $cashFlowReport->save();

        return $cashFlowReport;
    }
}