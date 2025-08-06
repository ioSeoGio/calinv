<?php

namespace src\ViewHelper\ExpressRating;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\DECalculator;
use src\IssuerRatingCalculator\ExpressRating\ExpressRatingCalculator;
use src\IssuerRatingCalculator\K1Calculator;
use src\IssuerRatingCalculator\K2Calculator;

class ExpressRatingViewHelper
{
    public static function render(Issuer $issuer, bool $simple): string
    {
        $result = '';

        foreach ($issuer->accountBalanceReports as $accountBalanceReport) {
            $k1 = K1Calculator::calculate($accountBalanceReport);
            $k2 = K2Calculator::calculate($accountBalanceReport);

            $value = $simple ? ExpressRatingCalculator::calculateSimple($k1, $k2) : ExpressRatingCalculator::calculate($k1, $k2);

            $result .= "$accountBalanceReport->_year: ";
            $result .= GoodBadValueViewHelper::execute($value, line: 1, moreBetter: false);
            $result .= '<br>';
        }
        return $result;
    }
}
