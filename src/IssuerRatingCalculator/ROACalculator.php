<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\FinanceReport\CashFlowReport\CashFlowReport;
use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport;

class ROACalculator
{
    public static function calculate(ProfitLossReport $profitLossReport, AccountingBalance $accountingBalance): float
    {
        return $profitLossReport->_210 /  $accountingBalance->_700;
    }
}