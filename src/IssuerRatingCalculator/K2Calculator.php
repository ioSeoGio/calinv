<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;

class K2Calculator
{
    public static function calculate(AccountingBalance $accountingBalance): float
    {
        return ($accountingBalance->_490 + $accountingBalance->_590 - $accountingBalance->_190) / $accountingBalance->_290;
    }
}