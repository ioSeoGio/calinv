<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;

class K1Calculator
{
    public static function calculate(AccountingBalance $accountingBalance): float
    {
        return $accountingBalance->_290 / $accountingBalance->_690;
    }
}