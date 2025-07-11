<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport;
use src\Entity\Issuer\Issuer;

class PSCalculator
{
    public static function calculate(Issuer $issuer, ProfitLossReport $profitLossReport): float
    {
        return CapitalizationByShareCalculator::calculate($issuer) / $profitLossReport->_010;
    }
}