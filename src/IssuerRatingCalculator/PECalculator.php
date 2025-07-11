<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport;
use src\Entity\Issuer\Issuer;

class PECalculator
{
    public static function calculate(Issuer $issuer, ProfitLossReport $report): ?float
    {
        return CapitalizationByShareCalculator::calculate($issuer) / $report->_240;
    }
}