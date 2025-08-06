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
            _021: $form->_021,
            _022: $form->_022,
            _023: $form->_023,
            _024: $form->_024,

            _030: $form->_030,
            _031: $form->_031,
            _032: $form->_032,
            _033: $form->_033,
            _040: $form->_040,

            _050: $form->_050,
            _051: $form->_051,
            _052: $form->_052,
            _053: $form->_053,
            _054: $form->_054,
            _055: $form->_055,

            _060: $form->_060,
            _061: $form->_061,
            _062: $form->_062,
            _063: $form->_063,
            _064: $form->_064,
            _070: $form->_070,

            _080: $form->_080,
            _081: $form->_081,
            _082: $form->_082,
            _083: $form->_083,
            _084: $form->_084,

            _090: $form->_090,
            _091: $form->_091,
            _092: $form->_092,
            _093: $form->_093,
            _094: $form->_094,
            _095: $form->_095,
            _100: $form->_100,

            _110: $form->_110,
            _120: $form->_120,
            _130: $form->_130,
            _140: $form->_140,
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