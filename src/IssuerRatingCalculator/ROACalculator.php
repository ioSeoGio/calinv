<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\FinanceReport\CashFlowReport\CashFlowReport;
use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport;

class ROACalculator
{
    public static function calculate(CashFlowReport $cashFlowReport, AccountingBalance $accountingBalance): float
    {
        return $cashFlowReport->_040 / $accountingBalance->_700;
    }
}