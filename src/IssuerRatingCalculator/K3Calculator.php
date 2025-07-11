<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;

class K3Calculator
{
    public static function calculate(AccountingBalance $accountingBalance): float
    {
        return ($accountingBalance->_690 + $accountingBalance->_590) / $accountingBalance->_300;
    }
}