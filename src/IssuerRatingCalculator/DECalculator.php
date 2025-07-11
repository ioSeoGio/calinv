<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;

class DECalculator
{
    public static function calculate(AccountingBalance $accountBalanceReport): float
    {
        return $accountBalanceReport->_590 / $accountBalanceReport->_490;
    }
}