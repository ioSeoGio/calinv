<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport;
use src\Entity\Issuer\Issuer;

class PECalculator
{
    public static function calculate(Issuer $issuer, ProfitLossReport $profitLossReport): float
    {
        return CapitalizationByShareCalculator::calculateInGrands($issuer) / $profitLossReport->_240;
    }

    public static function calculateByCapitalization(float $capitalizationInGrands, ProfitLossReport $profitLossReport): float
    {
        return $capitalizationInGrands / $profitLossReport->_240;
    }
}