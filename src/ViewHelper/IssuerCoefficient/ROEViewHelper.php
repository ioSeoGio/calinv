<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\ROECalculator;

class ROEViewHelper
{
    public static function render(Issuer $issuer): string
    {
        $result = '';

        $accountBalanceReports = $issuer->accountBalanceReports;
        $profitLossReports = $issuer->profitLossReports;

        for ($i = 0; $i < min(count($accountBalanceReports), count($profitLossReports)); $i++) {
            $value = ROECalculator::calculate($profitLossReports[$i], $accountBalanceReports[$i]);
            $result .= "{$profitLossReports[$i]->_year}: ";
            $result .= GoodBadValueViewHelper::execute($value * 100, line: 10, moreBetter: true, postfix: '%');
            $result .= '<br>';
        }

        return $result;
    }
}