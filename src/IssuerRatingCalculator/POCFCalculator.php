<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\FinanceReport\CashFlowReport\CashFlowReport;
use src\Entity\Issuer\Issuer;

class POCFCalculator
{
    public static function calculate(Issuer $issuer, CashFlowReport $cashFlowReport): float
    {
        return CapitalizationByShareCalculator::calculateInGrands($issuer) / $cashFlowReport->_040;
    }
}