<?php

namespace src\Integration\Legat;

use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\Dto\FinanceReportAccountingBalanceDto;
use src\Integration\Legat\Dto\FinanceReportCashFlowDto;
use src\Integration\Legat\Dto\FinanceReportProfitLossDto;

interface FinanceReportFetcherInterface
{
    public function getAccountingBalance(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportAccountingBalanceDto;

    public function getProfitLoss(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportProfitLossDto;
    public function getCashFlowReport(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportCashFlowDto;
}