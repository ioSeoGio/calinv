<?php

namespace src\ViewHelper;

use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\K2Calculator;
use src\IssuerRatingCalculator\K3Calculator;

class K3ViewHelper
{
    public static function render(Issuer $issuer): string
    {
        $result = '';

        foreach ($issuer->accountBalanceReports as $accountBalanceReport) {
            $value = K3Calculator::calculate($accountBalanceReport);
            $result .= "$accountBalanceReport->_year: ";
            $result .= GoodBadValueViewHelper::execute($value, line: 0.85, moreBetter: false);
            $result .= '<br>';
        }

        return $result;
    }
}