<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\FinanceReport\CashFlowReport\CashFlowReport;
use src\Entity\Issuer\Issuer;

class PFCFCalculator
{
    public static function calculate(Issuer $issuer, CashFlowReport $cashFlowReport): float
    {
        return CapitalizationByShareCalculator::calculateInGrands($issuer) / $cashFlowReport->_110;
    }

    public static function calculateByCapitalization(float $capitalizationInGrands, CashFlowReport $cashFlowReport): float
    {
        return $capitalizationInGrands / $cashFlowReport->_110;
    }
}