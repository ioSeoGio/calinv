<?php

namespace src\Integration\FinanceReport;

use src\Entity\Issuer\FinanceReport\CashFlowReport\CashFlowReport;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\FinanceReport\Dto\FinanceReportAccountingBalanceDto;
use src\Integration\FinanceReport\Dto\FinanceReportCashFlowDto;
use src\Integration\FinanceReport\Dto\FinanceReportProfitLossDto;

interface FinanceReportFetcherInterface
{
    public function getAccountingBalance(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportAccountingBalanceDto;

    public function getProfitLoss(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportProfitLossDto;
    public function getCashFlowReport(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportCashFlowDto;
}