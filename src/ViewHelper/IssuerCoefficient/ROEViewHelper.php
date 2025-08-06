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

    public static function getMathMLFormula(): string
    {
        return '<math xmlns="http://www.w3.org/1998/Math/MathML" class="math-large">
          <mfrac>
            <mrow>
              <mi>Чистая прибыль (210 строка ОПиУ)</mi>
            </mrow>
            <mrow>
              <mi>Капитал (490 строка ОББ)</mi>
            </mrow>
          </mfrac>
        </math>';
    }
}