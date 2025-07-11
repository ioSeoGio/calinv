<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport;

class ROECalculator
{
    public static function calculate(ProfitLossReport $profitLossReport, AccountingBalance $accountingBalance): float
    {
        return $profitLossReport->_210 / $accountingBalance->_490;
    }
}