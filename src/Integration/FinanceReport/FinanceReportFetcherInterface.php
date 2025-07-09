<?php

namespace src\Integration\FinanceReport;

use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\FinanceReport\Dto\FinanceReportAccountingBalanceDto;

interface FinanceReportFetcherInterface
{
    public function getAccountingBalance(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportAccountingBalanceDto;

    public function getProfitLoss(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportAccountingBalanceDto;
    public function getCapital(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportAccountingBalanceDto;
}