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
            _020: $form->_020,
            _030: $form->_030,
            _040: $form->_040,
            _050: $form->_050,
            _060: $form->_060,
            _070: $form->_070,
            _080: $form->_080,
            _090: $form->_090,

            _100: $form->_100,
            _101: $form->_101,
            _102: $form->_102,
            _103: $form->_103,
            _104: $form->_104,

            _110: $form->_110,
            _111: $form->_111,
            _112: $form->_112,

            _120: $form->_120,
            _121: $form->_121,
            _122: $form->_122,

            _130: $form->_130,
            _131: $form->_131,
            _132: $form->_132,
            _133: $form->_133,

            _140: $form->_140,
            _150: $form->_150,
            _160: $form->_160,
            _170: $form->_170,
            _180: $form->_180,
            _190: $form->_190,

            _210: $form->_210,
            _220: $form->_220,
            _230: $form->_230,
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