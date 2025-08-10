<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\Issuer;

class PBCalculator
{
    public static function calculate(Issuer $issuer, AccountingBalance $report): float
    {
        return CapitalizationByShareCalculator::calculateInGrands($issuer) / $report->_700;
    }

    public static function calculateByCapitalization(float $capitalizationInGrands, AccountingBalance $report): float
    {
        return $capitalizationInGrands / $report->_700;
    }
}